<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050807">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Setup — AISecurity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            try {
                const saved = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const dark = saved ? saved === 'dark' : prefersDark;
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) { document.documentElement.classList.add('dark'); }
        })();
    </script>
</head>
<body class="bg-[#020916] text-white min-h-screen flex items-center justify-center p-4 sm:p-6 relative" data-lights="auth">
    <a href="#code" class="skip-link">Skip to confirmation code</a>
    <x-terminal-background />
    <x-global-3d-scene />
    <x-global-hud-frame />

    <div class="w-full max-w-md relative z-10 py-8">
        <div class="auth-term p-7 sm:p-9">
            <span class="auth-term-corner tl" aria-hidden="true"></span>
            <span class="auth-term-corner br" aria-hidden="true"></span>

            <div class="ais-eyebrow mb-4">Two-factor authentication</div>
            <h1 class="font-display font-extrabold text-2xl tracking-tight text-white">Two-Factor Setup</h1>
            <p class="text-sm text-[#B6C8DD] mt-1.5 mb-6">Scan the QR code with your authenticator app, then confirm with a code.</p>

            @if(session('success'))
            <div class="term-alert term-alert-ok mb-5" role="status">
                
                <span class="text-white">{{ session('success') }}</span>
            </div>
            @endif
            @if($enabled)
            <div class="term-alert term-alert-ok mb-5" role="status">
                
                <span class="text-white">Two-factor authentication is currently <strong>enabled</strong>.</span>
            </div>
            @endif

            <div class="flex justify-center mb-4 bg-white p-4 border border-white/10">
                <img src="{{ $qrUrl }}" alt="MFA QR code" class="w-48 h-48">
            </div>
            @if($secret)<p class="term-hint text-center mb-6 break-all">MANUAL KEY: {{ $secret }}</p>@endif

            <form method="POST" action="{{ route('mfa.enable') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="code" class="term-field-label">Confirmation code</label>
                    <input type="text" id="code" name="code" required inputmode="numeric" autocomplete="one-time-code" maxlength="8"
                           class="term-input auth-code-input" placeholder="••••••">
                    @error('code') <p class="term-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="ais-btn-primary w-full !py-3.5 justify-center">Enable 2FA →</button>
            </form>

            @if($enabled)
            <form method="POST" action="{{ route('mfa.disable') }}" class="mt-6 space-y-4 border-t border-white/10 pt-6">
                @csrf
                <p class="term-hint">To disable, confirm your password plus a current code from your authenticator app.</p>
                <input type="password" name="password" required autocomplete="current-password" placeholder="Current password"
                       class="term-input auth-code-input !text-base">
                @error('password') <p class="term-error">{{ $message }}</p> @enderror
                <input type="text" name="code" required inputmode="numeric" maxlength="8" placeholder="Current code to disable"
                       class="term-input auth-code-input !text-base">
                <button class="ais-btn-ghost w-full justify-center !border-red-500/50 !text-red-400">Disable 2FA</button>
            </form>
            @endif

            @if(session('recovery_codes'))
            <div class="term-alert term-alert-warn mt-6 !block" role="alert">
                
                <ul class="font-mono text-xs grid grid-cols-2 gap-1 mt-2 text-white">@foreach(session('recovery_codes') as $c)<li>{{ $c }}</li>@endforeach</ul>
            </div>
            @endif
            @if($enabled)
            <div class="mt-4 border border-white/10 p-4 text-sm text-[#B6C8DD]">
                Recovery codes remaining: <strong class="text-white">{{ $recoveryCount }}</strong>
                <form method="POST" action="{{ route('mfa.recovery-codes') }}" class="mt-3 flex gap-2">
                    @csrf
                    <input type="password" name="password" required autocomplete="current-password" placeholder="Password" class="term-input flex-1 !text-sm text-center">
                    <input type="text" name="code" required inputmode="numeric" maxlength="8" placeholder="Current TOTP" class="term-input flex-1 !text-sm text-center">
                    <button class="ais-btn-ghost flex-shrink-0">Regenerate</button>
                </form>
            </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ auth()->user() && auth()->user()->isCustomer() ? route('portal.dashboard') : route('admin.dashboard') }}" class="ais-link">← Back to dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
