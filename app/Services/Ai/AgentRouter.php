<?php

namespace App\Services\Ai;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

/**
 * AgentRouter — selects the external agent runtime without business code
 * ever depending on one. A runtime is routable ONLY when it is enabled,
 * configured AND staging-verified; anything else yields an explicit reason
 * so callers fall back to provider chat / knowledge / human support.
 */
class AgentRouter
{
    public function adapter(string $runtime): ?AgentRuntimeInterface
    {
        return match ($runtime) {
            'hermes' => new HermesAgentAdapter(),
            'openclaw' => new OpenClawAdapter(),
            default => null,
        };
    }

    /**
     * @return array {adapter:?AgentRuntimeInterface, reason:string}
     */
    public function route(?string $preferred = null): array
    {
        $preferred = $preferred ?: (string) config('agent.runtime', 'none');
        $adapter = $this->adapter($preferred);
        if (! $adapter) {
            return ['adapter' => null, 'reason' => 'no_runtime_selected'];
        }
        if (! $this->isUsable($preferred)) {
            return ['adapter' => null, 'reason' => $preferred.'_not_usable'];
        }

        return ['adapter' => $adapter, 'reason' => 'ok'];
    }

    public function isUsable(string $runtime): bool
    {
        if (! (bool) config('agent.enabled', false)) {
            return false;
        }
        // Admin kill switch (cache-based, no .env writes): kills one runtime
        // or everything when set. Checked on every routing decision.
        if (\Illuminate\Support\Facades\Cache::get('agent:kill:all') || \Illuminate\Support\Facades\Cache::get("agent:kill:{$runtime}")) {
            return false;
        }
        $enabled = (bool) config("agent.{$runtime}_enabled", false);
        $verified = (bool) config("agent.{$runtime}.verified", false);
        $adapter = $this->adapter($runtime);
        if (! $adapter) {
            return false;
        }
        $configured = method_exists($adapter, 'isConfigured') ? $adapter->isConfigured() : false;

        return $enabled && $verified && $configured;
    }

    /** Real health states for the admin dashboard — never assumed. */
    public function health(string $runtime): array
    {
        $adapter = $this->adapter($runtime);
        if (! $adapter) {
            return ['runtime' => $runtime, 'enabled' => false, 'error' => 'unknown_runtime', 'checked_at' => now()->toDateTimeString()];
        }
        try {
            return $adapter->health();
        } catch (\Throwable $e) {
            Log::warning('Agent runtime health failed', ['runtime' => $runtime]);

            return ['runtime' => $runtime, 'enabled' => false, 'error' => 'health_failed', 'checked_at' => now()->toDateTimeString()];
        }
    }

    public function auditStatusCheck(?int $actorId, string $runtime, array $health): void
    {
        try {
            AuditLog::log('agent.health_check', 'ai', null,
                "actor={$actorId} runtime={$runtime} error=".($health['error'] ?? 'none'));
        } catch (\Throwable $e) {
            Log::warning('Agent health audit failed: '.$e->getMessage());
        }
    }
}
