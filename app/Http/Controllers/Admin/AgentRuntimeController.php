<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\Ai\AgentRouter;
use App\Services\Ai\RuntimeHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Agent runtime administration (Hermes / OpenClaw).
 *
 * Reports REAL states from live probes — never "connected" from config
 * alone. Secrets are masked always; full values never leave the server.
 * The kill switch is cache-based (instant, no .env writes).
 */
class AgentRuntimeController extends Controller
{
    public function index(AgentRouter $router)
    {
        $runtimes = [];
        foreach (['hermes', 'openclaw'] as $name) {
            $health = $router->health($name);
            $router->auditStatusCheck(auth()->id(), $name, $health);
            $runtimes[$name] = [
                'health' => $health,
                'status' => $this->statusOf($router, $name, $health),
                'killed' => (bool) Cache::get("agent:kill:{$name}"),
                'config' => $this->maskedConfig($name),
            ];
        }
        $globalKill = (bool) Cache::get('agent:kill:all');
        $recent = AuditLog::where('action', 'like', 'agent.%')->latest()->limit(20)->get();

        return view('admin.ai.runtime', compact('runtimes', 'globalKill', 'recent'));
    }

    /**
     * Explicit connection test. OpenClaw wake() DOES cause gateway activity
     * (stated on the button); Hermes test is a side-effect-free health
     * re-check unless send_test_message is explicitly ticked.
     */
    public function test(Request $request, AgentRouter $router, string $runtime)
    {
        abort_unless(in_array($runtime, ['hermes', 'openclaw'], true), 404);
        $request->validate(['send_test_message' => 'nullable|boolean']);

        $adapter = $router->adapter($runtime);
        abort_unless($adapter, 422, 'Unknown runtime.');
        $out = ['runtime' => $runtime];

        try {
            if ($runtime === 'openclaw') {
                $out['wake'] = $adapter->wake('AISecurity admin connection test (next-heartbeat).', 'next-heartbeat');
            } else {
                $out['health'] = $adapter->health();
                if ($request->boolean('send_test_message')) {
                    $out['chat'] = $adapter->chat([['role' => 'user', 'content' => 'Reply with the single word: ok']], ['max_tokens' => 16]);
                    // Never store the reply content in audit — metadata only.
                    $out['chat'] = ['success' => true, 'content_chars' => mb_strlen($out['chat']['content'] ?? '')];
                }
            }
            $result = 'success';
        } catch (\Throwable $e) {
            $out['error'] = $e instanceof \App\Services\Ai\AiProviderException ? $e->getCategory() : 'test_failed';
            $result = 'error';
        }

        try {
            AuditLog::log('agent.runtime_test', 'ai', null,
                'actor='.auth()->id().":admin runtime={$runtime} result={$result}");
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('admin.ai.runtime')->with(
            $result === 'success' ? 'success' : 'error',
            $result === 'success' ? ucfirst($runtime).' test completed — see states below.' : ucfirst($runtime).' test failed ('.($out['error'] ?? 'error').').'
        );
    }

    public function kill(Request $request, string $runtime)
    {
        abort_unless(in_array($runtime, ['hermes', 'openclaw', 'all'], true), 404);
        $request->validate(['action' => 'required|in:disable,enable']);

        $key = $runtime === 'all' ? 'agent:kill:all' : "agent:kill:{$runtime}";
        if ($request->action === 'disable') {
            Cache::put($key, ['by' => auth()->id(), 'at' => now()->toDateTimeString()], 86400 * 30);
        } else {
            Cache::forget($key);
        }

        try {
            AuditLog::log('agent.kill_switch', 'ai', null,
                'actor='.auth()->id().":admin runtime={$runtime} action={$request->action}");
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('admin.ai.runtime')->with('success', 'Runtime '.($request->action === 'disable' ? 'disabled' : 're-enabled').'.');
    }

    private function statusOf(AgentRouter $router, string $name, array $health): string
    {
        if (Cache::get('agent:kill:all') || Cache::get("agent:kill:{$name}")) {
            return 'disabled';
        }
        if (! ($health['enabled'] ?? false)) {
            return 'disabled';
        }
        if (! ($health['configured'] ?? false)) {
            return 'not_configured';
        }
        if (($health['error'] ?? null) === 'auth_failed' || ($health['last_error'] ?? null) === 'auth_failed') {
            return 'auth_failed';
        }
        if (($health['reachable'] ?? null) === false) {
            return 'unreachable';
        }
        if (! ($health['verified'] ?? false)) {
            return 'not_verified';
        }

        return $router->isUsable($name) ? 'ready' : 'disabled';
    }

    private function maskedConfig(string $name): array
    {
        if ($name === 'hermes') {
            return [
                'base_url' => (string) config('agent.hermes.base_url', '') ?: 'not set',
                'api_key' => RuntimeHttpClient::maskSecret((string) config('agent.hermes.api_key', '')),
                'model' => (string) config('agent.hermes.model', 'hermes-agent'),
                'timeout' => (int) config('agent.hermes.timeout', 60),
                'verified' => (bool) config('agent.hermes.verified', false) ? 'yes' : 'no',
            ];
        }

        return [
            'base_url' => (string) config('agent.openclaw.base_url', '') ?: 'not set',
            'token' => RuntimeHttpClient::maskSecret((string) config('agent.openclaw.token', '')),
            'agent_id' => (string) config('agent.openclaw.agent_id', '') ?: 'default (hooks)',
            'timeout' => (int) config('agent.openclaw.timeout', 60),
            'verified' => (bool) config('agent.openclaw.verified', false) ? 'yes' : 'no',
        ];
    }
}
