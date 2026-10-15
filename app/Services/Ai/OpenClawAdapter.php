<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * OpenClawAdapter — OpenClaw Gateway via its documented webhook ingress:
 *   POST {base}/hooks/agent  {message*, name?, agentId?, sessionKey?,
 *                             wakeMode?, deliver?, channel?, to?, model?,
 *                             thinking?, timeoutSeconds?}
 *     auth: Authorization: Bearer <token> (query-string tokens rejected)
 *   POST {base}/hooks/wake   {text*, mode?} — system event for main session
 *
 * Honest semantics: dispatching runs an ISOLATED agent turn whose reply
 * delivery is owned by the Gateway (channel config), NOT by this HTTP
 * response — the response schema is undocumented, so this adapter returns a
 * neutral envelope {accepted, status_code, session_key} and never invents
 * reply text. `chat()` therefore refuses: conversational delegation requires
 * staging verification of the reply path. Health is a side-effect-free
 * reachability probe; auth status comes only from real calls (401s).
 */
class OpenClawAdapter implements AgentRuntimeInterface
{
    private function cfg(string $key, $default = null)
    {
        return config('agent.openclaw.'.$key, $default);
    }

    public function isConfigured(): bool
    {
        return (bool) $this->cfg('base_url') && (bool) $this->cfg('token');
    }

    public function isVerified(): bool
    {
        return (bool) $this->cfg('verified', false);
    }

    private function guardUsable(): void
    {
        if (! (bool) config('agent.openclaw_enabled', false)) {
            throw new \RuntimeException('OpenClaw runtime disabled.');
        }
        if (! $this->isConfigured() || ! $this->isVerified()) {
            throw new \RuntimeException('OpenClaw runtime not verified — refusing dispatch.');
        }
    }

    /**
     * Dispatch an isolated agent turn. Documented fields only; unknown
     * options are dropped, never forwarded.
     *
     * @return array {accepted, status_code, session_key, error?}
     */
    public function dispatchAgentTurn(string $message, array $options = []): array
    {
        $this->guardUsable();
        $message = mb_substr(trim($message), 0, 4000);
        if ($message === '') {
            throw new \RuntimeException('OpenClaw dispatch requires a message.');
        }

        $payload = ['message' => $message, 'deliver' => false];
        if ($name = $options['name'] ?? null) {
            $payload['name'] = mb_substr((string) $name, 0, 80);
        }
        $payload['agentId'] = $options['agent_id'] ?? $this->cfg('agent_id') ?: 'hooks';
        $payload['agentId'] = mb_substr((string) $payload['agentId'], 0, 80);
        if (isset($options['timeout_seconds'])) {
            $payload['timeoutSeconds'] = max(5, min(600, (int) $options['timeout_seconds']));
        }
        if (isset($options['model'])) {
            $payload['model'] = mb_substr((string) $options['model'], 0, 120);
        }
        // NOTE: sessionKey overrides are rejected by default upstream unless
        // explicitly allowed — we generate our own key per dispatch.
        $sessionKey = 'aisupport:'.bin2hex(random_bytes(8));
        $payload['sessionKey'] = $sessionKey;

        $result = RuntimeHttpClient::post(
            $this->cfg('base_url').'/hooks/agent',
            ['Authorization' => 'Bearer '.$this->cfg('token')],
            $payload,
            (int) $this->cfg('timeout', 60),
            (int) $this->cfg('connect_timeout', 10)
        );

        if (! $result['ok']) {
            $this->rememberCheck($result['error']);

            throw new AiProviderException($result['error_category'] ?? AiProviderException::UNKNOWN, $result['status']);
        }
        $this->rememberCheck(null);
        Cache::put('agent:openclaw:last_verified_at', now()->toDateTimeString(), 86400);

        return ['accepted' => true, 'status_code' => $result['status'], 'session_key' => $sessionKey];
    }

    /**
     * Enqueue a system event on the main session (explicit admin/test use:
     * this DOES cause gateway activity — never call it from health checks).
     */
    public function wake(string $text, string $mode = 'next-heartbeat'): array
    {
        $this->guardUsable();
        $text = mb_substr(trim($text), 0, 1000);
        if ($text === '') {
            throw new \RuntimeException('OpenClaw wake requires text.');
        }
        if (! in_array($mode, ['now', 'next-heartbeat'], true)) {
            $mode = 'next-heartbeat';
        }

        $result = RuntimeHttpClient::post(
            $this->cfg('base_url').'/hooks/wake',
            ['Authorization' => 'Bearer '.$this->cfg('token')],
            ['text' => $text, 'mode' => $mode],
            (int) $this->cfg('timeout', 60),
            (int) $this->cfg('connect_timeout', 10)
        );

        if (! $result['ok']) {
            $this->rememberCheck($result['error']);

            throw new AiProviderException($result['error_category'] ?? AiProviderException::UNKNOWN, $result['status']);
        }
        $this->rememberCheck(null);

        return ['accepted' => true, 'status_code' => $result['status'], 'mode' => $mode];
    }

    public function chat(array $messages, array $options = []): array
    {
        throw new \RuntimeException('OpenClaw conversational chat is not wired: the reply path is undocumented. Use dispatchAgentTurn() for verified event dispatch.');
    }

    public function stream(array $messages, array $options = []): iterable
    {
        throw new \RuntimeException('OpenClaw streaming is not supported by this adapter.');
    }

    public function health(): array
    {
        $enabled = (bool) config('agent.openclaw_enabled', false);
        $out = [
            'runtime' => 'openclaw',
            'enabled' => $enabled,
            'verified' => $this->isVerified(),
            'configured' => $this->isConfigured(),
            'reachable' => null,
            'latency_ms' => null,
            'checked_at' => now()->toDateTimeString(),
            'error' => null,
        ];
        if (! $enabled) {
            $out['error'] = 'disabled';

            return $out;
        }
        if (! $this->isConfigured()) {
            $out['error'] = 'not_configured';

            return $out;
        }

        // Side-effect-free: any HTTP answer (even 404 on /) = reachable.
        // Never touches /hooks/* here (those trigger agent activity).
        $started = microtime(true);
        $probe = RuntimeHttpClient::reachable($this->cfg('base_url').'/', (int) $this->cfg('connect_timeout', 10));
        $out['latency_ms'] = (int) ((microtime(true) - $started) * 1000);
        if ($probe === true) {
            $out['reachable'] = true;
            $out['error'] = $this->isVerified() ? null : 'not_verified';
        } elseif ($probe === false) {
            $out['reachable'] = false;
            $out['error'] = 'unreachable';
        } else {
            $out['error'] = 'probe_indeterminate';
        }
        foreach (['agent:openclaw:last_error' => 'last_error', 'agent:openclaw:last_verified_at' => 'last_verified_at'] as $k => $label) {
            $v = Cache::get($k);
            if ($v) {
                $out[$label] = $v;
            }
        }

        return $out;
    }

    public function capabilities(): array
    {
        return [
            'runtime' => 'openclaw',
            'verified' => $this->isVerified(),
            'tools' => [],
            'ingress' => ['hooks_agent', 'hooks_wake'],
            'note' => 'Event dispatch only (deliver=false). No assumed reply semantics; conversational delegation requires staging proof.',
        ];
    }

    public function executeApprovedTool(string $tool, array $params = []): array
    {
        throw new \RuntimeException('OpenClaw direct tool execution is not offered — use AgentGateway tools.');
    }

    public function cancel(string $callId): bool
    {
        return false;
    }

    public function status(): string
    {
        if (! (bool) config('agent.openclaw_enabled', false)) {
            return 'disabled';
        }
        if (! $this->isConfigured()) {
            return 'not_configured';
        }
        $lastError = Cache::get('agent:openclaw:last_error');
        if ($lastError === 'auth_failed') {
            return 'auth_failed';
        }
        $h = $this->health();

        return $h['reachable'] === false ? 'unreachable' : ($this->isVerified() ? 'ready' : 'not_verified');
    }

    private function rememberCheck(?string $error): void
    {
        try {
            if ($error) {
                Cache::put('agent:openclaw:last_error', $error, 3600);
                Log::warning('OpenClaw runtime call failed', ['error' => $error]);
            } else {
                Cache::forget('agent:openclaw:last_error');
            }
        } catch (\Throwable $e) {
            // observability must never break the call path
        }
    }
}
