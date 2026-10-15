<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * HermesAgentAdapter — Nous Research Hermes Agent via its documented
 * OpenAI-compatible API server:
 *   POST {base}/v1/chat/completions  {model, messages} + Bearer API_SERVER_KEY
 *   GET  {base}/health               (side-effect-free liveness)
 *   GET  {base}/v1/capabilities      (machine-readable feature flags)
 *
 * The gateway must run on loopback / private network — this adapter never
 * exposes anything; it only calls the configured backend URL with timeouts.
 * `chat()` refuses unless the runtime is enabled, configured AND staging
 * verified (HERMES_VERIFIED). Response parsing is defensive: only
 * choices[0].message.content is accepted; anything else is an
 * invalid-response error, never a fabricated reply.
 */
class HermesAgentAdapter implements AgentRuntimeInterface
{
    public const DEFAULT_MODEL = 'hermes-agent';

    private function cfg(string $key, $default = null)
    {
        return config('agent.hermes.'.$key, $default);
    }

    public function isConfigured(): bool
    {
        return (bool) $this->cfg('base_url') && (bool) $this->cfg('api_key');
    }

    public function isVerified(): bool
    {
        return (bool) $this->cfg('verified', false);
    }

    public function chat(array $messages, array $options = []): array
    {
        if (! (bool) config('agent.hermes_enabled', false)) {
            throw new \RuntimeException('Hermes runtime disabled.');
        }
        if (! $this->isConfigured() || ! $this->isVerified()) {
            throw new \RuntimeException('Hermes runtime not verified — refusing chat.');
        }

        $clean = [];
        foreach (array_slice($messages, -20) as $m) {
            $role = $m['role'] ?? 'user';
            $content = mb_substr((string) ($m['content'] ?? ''), 0, 8000);
            if (! in_array($role, ['system', 'user', 'assistant'], true) || $content === '') {
                continue;
            }
            $clean[] = ['role' => $role, 'content' => $content];
        }
        if ($clean === []) {
            throw new \RuntimeException('Hermes chat requires at least one message.');
        }

        $result = RuntimeHttpClient::post(
            $this->cfg('base_url').'/v1/chat/completions',
            ['Authorization' => 'Bearer '.$this->cfg('api_key')],
            [
                'model' => $options['model'] ?? $this->cfg('model', self::DEFAULT_MODEL),
                'messages' => $clean,
                'temperature' => $options['temperature'] ?? 0.2,
                'max_tokens' => min((int) ($options['max_tokens'] ?? 512), 2048),
            ],
            (int) $this->cfg('timeout', 60),
            (int) $this->cfg('connect_timeout', 10)
        );

        if (! $result['ok']) {
            $this->rememberCheck($result['error']);

            throw new AiProviderException($result['error_category'] ?? AiProviderException::UNKNOWN, $result['status']);
        }

        $content = $result['json']['choices'][0]['message']['content'] ?? null;
        if (! is_string($content) || trim($content) === '') {
            $this->rememberCheck('invalid_response');

            throw AiProviderException::invalidResponse();
        }
        $this->rememberCheck(null);

        return ['success' => true, 'via' => 'hermes', 'content' => $content];
    }

    public function stream(array $messages, array $options = []): iterable
    {
        throw new \RuntimeException('Hermes streaming is not supported by this adapter — use chat().');
    }

    public function health(): array
    {
        $enabled = (bool) config('agent.hermes_enabled', false);
        $out = [
            'runtime' => 'hermes',
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

        $started = microtime(true);
        $probe = RuntimeHttpClient::reachable($this->cfg('base_url').'/health', (int) $this->cfg('connect_timeout', 10));
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
        $last = Cache::get('agent:hermes:last_error');
        if ($out['error'] === null && $last) {
            $out['last_error'] = $last;
        }

        return $out;
    }

    public function capabilities(): array
    {
        $note = 'Hermes API server (OpenAI-compatible). Verified chat only; no assumed tool/streaming semantics.';
        if (! $this->isConfigured() || ! $this->isVerified()) {
            return ['runtime' => 'hermes', 'verified' => false, 'tools' => [], 'note' => $note];
        }
        // Defensive: any 2xx decode is informational; unknown fields ignored.
        $result = RuntimeHttpClient::post(
            $this->cfg('base_url').'/v1/capabilities',
            ['Authorization' => 'Bearer '.$this->cfg('api_key')],
            [],
            15,
            (int) $this->cfg('connect_timeout', 10)
        );

        return [
            'runtime' => 'hermes',
            'verified' => true,
            'tools' => [],
            'remote_flags' => $result['ok'] && is_array($result['json']) ? array_keys($result['json']) : [],
            'note' => $note,
        ];
    }

    public function executeApprovedTool(string $tool, array $params = []): array
    {
        // Hermes tool execution stays inside the Hermes runtime under its own
        // approval policy; this backend never forwards blind tool calls.
        // Approved business actions always run through AgentGateway instead.
        throw new \RuntimeException('Hermes direct tool execution is not offered — use AgentGateway tools.');
    }

    public function cancel(string $callId): bool
    {
        // No documented HTTP cancel endpoint confirmed — report honestly.
        return false;
    }

    public function status(): string
    {
        if (! (bool) config('agent.hermes_enabled', false)) {
            return 'disabled';
        }
        if (! $this->isConfigured()) {
            return 'not_configured';
        }
        $h = $this->health();

        return $h['reachable'] === false ? 'unreachable' : ($this->isVerified() ? 'ready' : 'not_verified');
    }

    private function rememberCheck(?string $error): void
    {
        try {
            if ($error) {
                Cache::put('agent:hermes:last_error', $error, 3600);
                Log::warning('Hermes runtime call failed', ['error' => $error]);
            } else {
                Cache::forget('agent:hermes:last_error');
            }
        } catch (\Throwable $e) {
            // observability must never break the call path
        }
    }
}
