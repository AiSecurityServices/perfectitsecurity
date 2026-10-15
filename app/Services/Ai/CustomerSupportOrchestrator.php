<?php

namespace App\Services\Ai;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * CustomerSupportOrchestrator — deterministic request triage + explicit
 * routing for customer questions (spec §8).
 *
 * Classification is RULES-FIRST (never model-only for auth/priority/security
 * decisions). Runtime dispatch is allowed only for verified runtimes, only
 * for staff-approved contexts, and every dispatch/fallback is audited with
 * metadata only. The orchestrator never invents ticket numbers, replies, or
 * provider connectivity.
 */
class CustomerSupportOrchestrator
{
    public const TYPE_SERVICE_ENQUIRY = 'service_enquiry';

    public const TYPE_TECHNICAL_SUPPORT = 'technical_support';

    public const TYPE_TICKET_FOLLOWUP = 'ticket_followup';

    public const TYPE_NEW_INCIDENT = 'new_incident';

    public const TYPE_QUOTE_REQUEST = 'quote_request';

    public const TYPE_AI_INTEGRATION = 'ai_integration';

    public const TYPE_BILLING = 'billing_enquiry';

    public const TYPE_ACCOUNT_ACCESS = 'account_access';

    public const TYPE_SECURITY_INCIDENT = 'security_incident';

    public const TYPE_HUMAN_REQUEST = 'human_request';

    public const TYPE_OUT_OF_SCOPE = 'out_of_scope';

    /**
     * Deterministic classification. Returns one of the TYPE_* constants.
     * Ambiguous input → out_of_scope (human triages), never a guess.
     */
    public function classify(string $message): string
    {
        $t = mb_strtolower(trim($message));
        if ($t === '') {
            return self::TYPE_OUT_OF_SCOPE;
        }

        $has = fn (array $needles): bool => $this->containsAny($needles, $t);

        if ($has(['human', 'agent please', 'real person', 'call me', 'phone me', 'talk to someone', 'support team'])) {
            return self::TYPE_HUMAN_REQUEST;
        }
        if ($has(['hack', 'breach', 'ransomware', 'malware', 'phish', 'intrusion', 'compromis', 'incident', 'data leak', 'ddos'])) {
            return self::TYPE_SECURITY_INCIDENT;
        }
        if ($has(['my ticket', 'ticket tk-', 'ticket #', 'follow up on', 'status of my', 'update on my request'])) {
            return self::TYPE_TICKET_FOLLOWUP;
        }
        if ($has(['quote', 'quotation', 'how much', 'pricing', 'price for', 'cost of', 'estimate'])) {
            return self::TYPE_QUOTE_REQUEST;
        }
        if ($has(['ai agent', 'integrate ai', 'ai assistant', 'chatbot', 'automate', 'hermes', 'openclaw', 'rag', 'llm', 'model'])) {
            return self::TYPE_AI_INTEGRATION;
        }
        if ($has(['invoice', 'billing', 'payment', 'refund', 'charge', 'receipt'])) {
            return self::TYPE_BILLING;
        }
        if ($has(['login', 'password', 'mfa', 'locked out', 'access my account', 'reset my'])) {
            return self::TYPE_ACCOUNT_ACCESS;
        }
        if ($has(['not working', 'stopped working', 'error', 'broken', 'down', 'outage', 'failed', 'cannot', "can't", 'issue with', 'problem with', 'help with'])) {
            return str_contains($t, 'urgent') || str_contains($t, 'critical') || str_contains($t, 'emergency')
                ? self::TYPE_NEW_INCIDENT
                : self::TYPE_TECHNICAL_SUPPORT;
        }
        if ($has(['service', 'support', 'help', 'what do you offer', 'do you provide', 'managed it', 'microsoft 365', 'cloud', 'backup', 'wifi', 'vpn', 'server'])) {
            return self::TYPE_SERVICE_ENQUIRY;
        }

        return self::TYPE_OUT_OF_SCOPE;
    }

    /**
     * Route a classified request to a capability with explicit fallback.
     *
     * @return array {type, capability, fallback, reason}
     *   capability: knowledge|quote|human|runtime|helpdesk
     */
    public function route(string $message, ?User $user = null, array $context = []): array
    {
        $type = $this->classify($message);
        $router = app(AgentRouter::class);

        $route = match ($type) {
            self::TYPE_SECURITY_INCIDENT => ['capability' => 'human', 'fallback' => 'helpdesk', 'reason' => 'security_incident_auto_escalate'],
            self::TYPE_HUMAN_REQUEST => ['capability' => 'human', 'fallback' => 'helpdesk', 'reason' => 'customer_requested_human'],
            self::TYPE_QUOTE_REQUEST => ['capability' => 'quote', 'fallback' => 'knowledge', 'reason' => 'quote_workflow'],
            self::TYPE_TICKET_FOLLOWUP, self::TYPE_BILLING, self::TYPE_ACCOUNT_ACCESS => ['capability' => 'helpdesk', 'fallback' => 'human', 'reason' => 'account_scoped_workflow'],
            self::TYPE_NEW_INCIDENT => ['capability' => 'helpdesk', 'fallback' => 'human', 'reason' => 'incident_triage'],
            default => ['capability' => 'knowledge', 'fallback' => 'human', 'reason' => 'knowledge_first'],
        };

        // Runtime delegation is opt-in per call AND requires a verified
        // runtime AND a staff-approved context — never silent provider swaps.
        if (! empty($context['prefer_runtime'])) {
            $r = $router->route($context['runtime'] ?? null);
            $staffApproved = ($user && $user->isStaff()) || ! empty($context['human_approval_id']);
            if ($r['adapter'] && $staffApproved) {
                $route = ['capability' => 'runtime', 'fallback' => 'knowledge', 'reason' => 'verified_runtime_staff_approved', 'runtime' => $context['runtime'] ?? config('agent.runtime')];
            } else {
                $route['runtime_denied'] = $r['reason'].($staffApproved ? '' : '+staff_approval_missing');
            }
        }

        return ['type' => $type] + $route;
    }

    /**
     * Dispatch to a verified runtime. Staff-approved contexts only.
     * Returns a neutral envelope — never invented replies or ticket refs.
     */
    public function dispatchToRuntime(?User $actor, string $runtime, string $message, array $options = []): array
    {
        $router = app(AgentRouter::class);
        $r = $router->route($runtime);
        if (! $r['adapter']) {
            $this->audit($actor, $runtime, 'refused', $r['reason']);

            return ['success' => false, 'error' => 'runtime_not_usable', 'reason' => $r['reason'], 'fallback' => 'knowledge'];
        }
        if (! $actor || ! $actor->isStaff()) {
            $this->audit($actor, $runtime, 'refused', 'staff_only');

            return ['success' => false, 'error' => 'staff_only', 'fallback' => 'knowledge'];
        }

        try {
            if ($runtime === 'openclaw' && method_exists($r['adapter'], 'dispatchAgentTurn')) {
                $out = $r['adapter']->dispatchAgentTurn($message, $options);
            } elseif ($runtime === 'hermes') {
                $out = $r['adapter']->chat([['role' => 'user', 'content' => $message]], $options);
            } else {
                return ['success' => false, 'error' => 'unsupported_runtime', 'fallback' => 'knowledge'];
            }
            $this->audit($actor, $runtime, 'dispatched', null);

            return ['success' => true, 'runtime' => $runtime] + $out;
        } catch (\Throwable $e) {
            $this->audit($actor, $runtime, 'error', get_class($e));

            return ['success' => false, 'error' => 'runtime_error', 'fallback' => 'knowledge'];
        }
    }

    private function audit(?User $actor, string $runtime, string $result, ?string $reason): void
    {
        try {
            AuditLog::log('agent.orchestrator', 'ai', null,
                'actor='.($actor ? "{$actor->id}:{$actor->role}" : 'guest')
                ." runtime={$runtime} result={$result}".($reason ? " reason={$reason}" : ''));
        } catch (\Throwable $e) {
            Log::warning('Orchestrator audit failed: '.$e->getMessage());
        }
    }

    /** Dependency-free substring matcher for deterministic classification. */
    private function containsAny(array $needles, string $haystack): bool
    {
        foreach ($needles as $n) {
            if (str_contains($haystack, $n)) {
                return true;
            }
        }

        return false;
    }
}
