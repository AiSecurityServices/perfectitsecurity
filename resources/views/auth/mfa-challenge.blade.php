<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050807">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Check — AISecurity</title>
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
    <a href="#code" class="skip-link">Skip to verification code</a>
    <x-terminal-background />
    <x-global-3d-scene />
    <x-global-hud-frame />

    <div class="w-full max-w-md relative z-10">
        <div class="auth-term p-7 sm:p-9">
            <span class="auth-term-corner tl" aria-hidden="true"></span>
            <span class="auth-term-corner br" aria-hidden="true"></span>

            <div class="ais-eyebrow mb-4">Two-factor authentication</div>
            <h1 class="font-display font-extrabold text-2xl tracking-tight text-white">Two-Factor Check</h1>
            <p class="text-sm text-[#B6C8DD] mt-1.5 mb-6">Enter the 6-digit code from your authenticator app — or a recovery code (XXXX-XXXX).</p>

            @if(session('success'))
            <div class="term-alert term-alert-ok mb-5" role="status">
                
                <span class="text-white">{{ session('success') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('mfa.verify') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="code" class="term-field-label">Authentication code</label>
                    <input type="text" id="code" name="code" required autofocus autocomplete="one-time-code" maxlength="12"
                           class="term-input auth-code-input" placeholder="••••••">
                    @error('code') <p class="term-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="ais-btn-primary w-full !py-3.5 justify-center">Verify →</button>
            </form>
            <form method="POST" action="{{ route('logout') }}" class="mt-5 text-center">
                @csrf
                <button class="font-mono text-[11px] tracking-[0.14em] text-[#7FB7E8] hover:text-white uppercase transition-colors">Sign out instead</button>
            </form>
        </div>
    </div>
</body>
</html>
