@extends('layouts.app')
@section('page-title', 'Agent Runtimes — Hermes & OpenClaw')

@section('content')
<div class="space-y-6">
    <x-page-header title="Agent Runtimes" sys="SYSTEM://AGENTS" />

    <div class="term-panel p-5">
        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
            External agent runtimes are <strong>optional automation engines</strong>. States below come from live
            probes — a runtime is never reported as connected from configuration alone. Enabling or verifying a
            runtime requires server environment variables (<code>HERMES_*</code>, <code>OPENCLAW_*</code>) plus
            staging proof; this screen only inspects, tests explicitly, and kills. Full setup:
            <code>docs/AGENT_RUNTIME_SETUP.md</code>. Runtimes run on loopback / private network only.
        </p>
        @if($globalKill)
        <p class="mt-3 text-sm font-semibold text-red-600 dark:text-red-400">Global kill switch ACTIVE — all runtimes disabled.</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($runtimes as $name => $r)
        <div class="term-panel p-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white capitalize">{{ $name }}</h2>
                <span class="badge {{ $r['status'] === 'ready' ? 'badge-success' : ($r['status'] === 'disabled' ? 'badge' : 'badge-warning') }}">{{ $r['status'] }}</span>
            </div>
            <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                @foreach($r['config'] as $k => $v)
                <dt class="text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $k }}</dt>
                <dd class="text-gray-900 dark:text-white font-mono text-xs break-all">{{ $v }}</dd>
                @endforeach
                <dt class="text-gray-500 dark:text-gray-400 font-mono text-xs">reachable</dt>
                <dd class="text-gray-900 dark:text-white font-mono text-xs">{{ var_export($r['health']['reachable'] ?? null, true) }}</dd>
                <dt class="text-gray-500 dark:text-gray-400 font-mono text-xs">latency_ms</dt>
                <dd class="text-gray-900 dark:text-white font-mono text-xs">{{ $r['health']['latency_ms'] ?? '—' }}</dd>
                <dt class="text-gray-500 dark:text-gray-400 font-mono text-xs">error</dt>
                <dd class="text-gray-900 dark:text-white font-mono text-xs">{{ $r['health']['error'] ?? 'none' }}</dd>
                <dt class="text-gray-500 dark:text-gray-400 font-mono text-xs">checked_at</dt>
                <dd class="text-gray-900 dark:text-white font-mono text-xs">{{ $r['health']['checked_at'] ?? '—' }}</dd>
            </dl>
            <div class="mt-4 flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.ai.runtime.test', $name) }}">
                    @csrf
                    @if($name === 'hermes')
                    <label class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mr-2">
                        <input type="checkbox" name="send_test_message" value="1"> send one test message (spends budget)
                    </label>
                    @endif
                    <button type="submit" class="btn btn-sm btn-secondary" @if($name === 'openclaw') title="Sends a next-heartbeat wake event to the gateway (causes gateway activity)" @endif>Run connection test</button>
                </form>
                <form method="POST" action="{{ route('admin.ai.runtime.kill', $name) }}">
                    @csrf
                    <input type="hidden" name="action" value="{{ $r['killed'] ? 'enable' : 'disable' }}">
                    <button type="submit" class="btn btn-sm {{ $r['killed'] ? 'btn-success' : 'btn-destructive' }}">{{ $r['killed'] ? 'Re-enable' : 'Kill switch' }}</button>
                </form>
            </div>
            @if($name === 'openclaw')
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Test sends a <code>next-heartbeat</code> wake event — it causes gateway activity by design.</p>
            @endif
        </div>
        @endforeach
    </div>

    <div class="term-panel p-5">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Global kill switch</h2>
            <form method="POST" action="{{ route('admin.ai.runtime.kill', 'all') }}">
                @csrf
                <input type="hidden" name="action" value="{{ $globalKill ? 'enable' : 'disable' }}">
                <button type="submit" class="btn btn-sm {{ $globalKill ? 'btn-success' : 'btn-destructive' }}">{{ $globalKill ? 'Release global kill' : 'Disable all runtimes' }}</button>
            </form>
        </div>
    </div>

    <div class="term-panel p-5">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Recent agent audit (metadata only)</h2>
        <ul class="space-y-2 text-sm font-mono">
            @forelse($recent as $log)
            <li class="text-gray-600 dark:text-gray-300"><span class="text-gray-400">{{ $log->created_at }}</span> {{ $log->action }} — {{ $log->description }}</li>
            @empty
            <li class="text-gray-500">No agent activity recorded yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
