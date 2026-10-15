@extends('layouts.public')

@section('title', 'AISecurity — Advanced IT Support Meets the Power of AI')
@section('description', 'We build, secure and manage your digital future with AI agents, reliable IT support, and enterprise-grade cybersecurity.')

@section('content')
@php
    // Eight AI agent spotlight cards in reference order. Copy is benefit-led
    // and honest (no 24/7 or capability claims beyond the catalogue).
    $spotBySlug = isset($aiSpotlight) ? $aiSpotlight->keyBy('slug') : collect();
    $aiCards = [
        ['slug' => 'ai-customer-service-agents', 'title' => 'Customer Support Agents', 'desc' => 'Provide instant, accurate and friendly customer support.', 'bg' => 'rgba(22,139,255,.18)', 'fg' => '#4DA3FF', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>'],
        ['slug' => 'ai-sales-research-agents', 'title' => 'Sales & Research Agents', 'desc' => 'Find leads, analyze data and boost your sales.', 'bg' => 'rgba(255,180,84,.16)', 'fg' => '#FFB454', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l4-4 3 3 5-6"/>'],
        ['slug' => 'ai-coding-assistant-configuration', 'title' => 'Coding Assistant Agents', 'desc' => 'Write, debug and optimize code with AI.', 'bg' => 'rgba(121,88,255,.18)', 'fg' => '#9275FF', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-4 3 4 3m8-6l4 3-4 3M14 5l-4 14"/>'],
        ['slug' => 'business-process-automation-ai', 'title' => 'Business Process Automation', 'desc' => 'Automate workflows and save hours of work.', 'bg' => 'rgba(0,230,122,.14)', 'fg' => '#00E67A', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10M18 16l2 2 3-3"/>'],
        ['slug' => 'local-private-ai-deployment', 'title' => 'Local & Private AI Deployment', 'desc' => 'Your data, your control, on-site or in the cloud.', 'bg' => 'rgba(0,207,255,.14)', 'fg' => '#00CFFF', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 11l9-8 9 8M5 10v10h5v-6h4v6h5V10M12 3v0"/>'],
        ['slug' => 'ai-security-hardening', 'title' => 'AI Security Hardening', 'desc' => 'Protect your AI systems and data with advanced security layers.', 'bg' => 'rgba(77,163,255,.16)', 'fg' => '#4DA3FF', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3zM9.2 12l1.9 1.9L15 10"/>'],
        ['slug' => 'ai-agent-platform-setup', 'title' => 'AI Agent Platform Setup', 'desc' => 'Build and manage your own AI agent ecosystem.', 'bg' => 'rgba(0,207,255,.14)', 'fg' => '#00CFFF', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/>'],
        ['slug' => 'managed-ai-support', 'title' => 'Managed AI Support', 'desc' => 'Ongoing monitoring, updates and expert help.', 'bg' => 'rgba(255,92,92,.14)', 'fg' => '#FF8A8A', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2zM18 19a4 4 0 01-4 3h-2"/>'],
    ];
    $svc = fn($cat) => route('services.index').'?category='.$cat;
@endphp

<div class="ais-page">
{{-- ═════════ HERO ═════════ --}}
<section class="relative w-full overflow-hidden" aria-labelledby="hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-16 sm:pt-20 lg:pt-24 pb-12 lg:pb-16 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <p class="ais-eyebrow reveal" style="letter-spacing:.08em; text-transform:none; font-size:12.5px; font-weight:500; color:#7FB7E8;">Your Trusted Partner in AI-Powered IT &amp; Security</p>
            <h1 id="hero-heading" class="mt-4 font-extrabold tracking-tight leading-[1.05] text-4xl sm:text-5xl xl:text-[3.6rem] reveal" style="font-family:Sora,Inter,system-ui,sans-serif;">
                <span class="text-white">Advanced IT Support</span><br>
                <span class="ais-title-hl">Meets the Power of AI</span>
            </h1>
            <p class="mt-5 text-[14px] sm:text-[15px] leading-relaxed max-w-xl reveal delay-100" style="color:#B6C8DD;">We build, secure and manage your digital future with AI agents, 24/7 support, and enterprise-grade security.</p>
            <ul class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 max-w-xl reveal delay-200" aria-label="Service highlights">
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/></svg><span><strong class="text-white font-semibold">AI Agents</strong> &amp; Automation</span></li>
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/></svg><span><strong class="text-white font-semibold">Cybersecurity</strong> &amp; Threat Protection</span></li>
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/></svg><span><strong class="text-white font-semibold">Cloud &amp; Infrastructure</strong> Management</span></li>
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z"/></svg><span><strong class="text-white font-semibold">24/7 Support</strong> &amp; Monitoring</span></li>
            </ul>
            <div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3 reveal delay-200">
                <a href="{{ route('ai.index') }}" class="ais-btn-primary">Explore Our AI Solutions
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="{{ route('contact') }}" class="ais-btn-ghost">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z"/></svg>
                    Talk to an Expert</a>
            </div>
        </div>
        <div class="hero-globe-stage reveal delay-100" data-globe="live" aria-hidden="true">
            <div class="hero-globe-fallback"></div>
            <canvas class="hero-globe-canvas" tabindex="-1"></canvas>
            <div class="hero-float hero-float--tl"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.5"/><path stroke-linecap="round" d="M5 20c1.5-3.5 4-5 7-5s5.5 1.5 7 5"/></svg>AI Agents</strong></div>
            <div class="hero-float hero-float--tr"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3m0 14v3M2 12h3m14 0h3M5 5l2 2m10 10l2 2M19 5l-2 2M7 17l-2 2"/></svg>Automation</strong></div>
            <div class="hero-float hero-float--ml"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/></svg>IT Support</strong></div>
            <div class="hero-float hero-float--mr"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/></svg>Cloud</strong></div>
            <div class="hero-float hero-float--br"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z"/></svg>24/7 Support</strong></div>
            <div class="hero-float hero-float--bc"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/></svg>Cybersecurity</strong></div>
        </div>
    </div>
</section>

{{-- ═════════ AI AGENT SERVICES ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="agents-heading" style="background:linear-gradient(180deg,#020916,#03101f);">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-3 reveal">
            <p class="ais-eyebrow">AI Agent Services</p>
            <h2 id="agents-heading" class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">Intelligent Agents for Every Business Need</h2>
            <p class="ais-sub mt-4 text-[13px] leading-relaxed">From customer support to sales, research, coding and automation — our AI agents work around the clock to help you scale faster, reduce costs and improve productivity.</p>
            <a href="{{ route('ai.index') }}" class="ais-link mt-5">Explore All AI Agents
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        </div>
        <div class="lg:col-span-9">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3.5">
                @foreach($aiCards as $card)
                @php $hit = $spotBySlug->get($card['slug']); $url = $hit ? route('ai.show', $hit->slug) : route('ai.index'); @endphp
                <a href="{{ $url }}" class="ais-card group block p-4 reveal" aria-label="{{ $card['title'] }} — view service">
                    <div class="flex items-start justify-between gap-2">
                        <span class="ais-icon" style="background:{{ $card['bg'] }}; color:{{ $card['fg'] }};" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $card['icon'] !!}</svg></span>
                        <svg class="w-4 h-4 mt-1 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="#7FB7E8" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </div>
                    <h3 class="mt-3 text-[13.5px] font-bold text-white leading-snug">{{ $card['title'] }}</h3>
                    <p class="mt-1.5 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $card['desc'] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ═════════ WHY AISECURITY ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="why-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-6 items-stretch">
        <div class="lg:col-span-4 reveal">
            <p class="ais-eyebrow">Why AISecurity</p>
            <h2 id="why-heading" class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">More Than IT Support — We Build Smarter Solutions</h2>
            <p class="ais-sub mt-4 text-[13px] leading-relaxed">AISecurity combines expert IT support with cutting-edge AI technology to give you a secure, scalable and future-ready digital environment.</p>
            <ul class="mt-5 space-y-2.5 text-[13px]" style="color:#DCEBFF;">
                @foreach(['Certified AI & Security Experts', 'Tailored Solutions for Every Business', 'Enterprise-Grade Security & Compliance', 'Proven Track Record & Client Satisfaction'] as $b)
                <li class="flex items-start gap-2.5"><span class="mt-0.5 inline-flex w-4.5 h-4.5 w-[18px] h-[18px] rounded-full items-center justify-center flex-shrink-0" style="background:rgba(0,207,255,.15); border:1px solid rgba(0,207,255,.5);" aria-hidden="true"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>{{ $b }}</li>
                @endforeach
            </ul>
            <a href="{{ route('about') }}" class="ais-btn-ghost mt-6 !py-2 !text-[12.5px]">Learn More About Us
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        </div>
        <div class="lg:col-span-5 reveal delay-100">
            <div class="ais-robot h-full p-6 flex items-center justify-center" role="img" aria-label="Futuristic AI support robot illustration">
                <svg viewBox="0 0 320 340" class="w-full max-w-[300px]" aria-hidden="true">
                    <defs>
                        <linearGradient id="rbMetal" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#E8F1FF"/><stop offset=".5" stop-color="#9DB4D0"/><stop offset="1" stop-color="#5B7191"/></linearGradient>
                        <linearGradient id="rbGlow" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#168BFF"/><stop offset="1" stop-color="#00CFFF"/></linearGradient>
                        <radialGradient id="rbEye" cx=".5" cy=".5" r=".5"><stop offset="0" stop-color="#CFf6ff"/><stop offset=".5" stop-color="#00CFFF"/><stop offset="1" stop-color="#0a2540"/></radialGradient>
                    </defs>
                    <ellipse cx="160" cy="318" rx="90" ry="12" fill="rgba(0,207,255,.18)"/>
                    <rect x="110" y="200" width="100" height="95" rx="26" fill="url(#rbMetal)"/>
                    <rect x="132" y="222" width="56" height="44" rx="12" fill="#041326" stroke="url(#rbGlow)" stroke-width="3"/>
                    <circle cx="160" cy="244" r="12" fill="url(#rbGlow)" opacity=".9"/>
                    <path d="M104 208 L78 160 L96 152 L118 196 Z" fill="url(#rbMetal)"/>
                    <path d="M216 208 L242 160 L224 152 L202 196 Z" fill="url(#rbMetal)"/>
                    <ellipse cx="160" cy="120" rx="72" ry="80" fill="url(#rbMetal)"/>
                    <path d="M100 108 Q160 60 220 108 L214 150 Q160 128 106 150 Z" fill="#0b1a33"/>
                    <ellipse cx="160" cy="132" rx="48" ry="34" fill="#0b1a33"/>
                    <ellipse cx="142" cy="130" rx="13" ry="15" fill="url(#rbEye)"/>
                    <ellipse cx="180" cy="130" rx="13" ry="15" fill="url(#rbEye)"/>
                    <circle cx="96" cy="136" r="14" fill="none" stroke="url(#rbGlow)" stroke-width="5"/>
                    <rect x="146" y="196" width="28" height="14" rx="7" fill="#0b1a33" stroke="url(#rbGlow)" stroke-width="2"/>
                </svg>
                <div class="absolute top-4 right-4 w-[168px] rounded-xl p-3.5 text-left" style="background:linear-gradient(180deg, rgba(13,32,58,.92), rgba(7,26,48,.92)); border:1px solid rgba(0,207,255,.45); box-shadow:0 14px 40px -12px rgba(0,207,255,.4);" aria-hidden="true">
                    <p class="text-[12px] font-extrabold text-white leading-tight">AI Powered<br>IT Support</p>
                    <ul class="mt-2.5 space-y-1.5 text-[11.5px]" style="color:#B6C8DD;">
                        @foreach(['Smarter','Faster','Safer','Always On'] as $w)
                        <li class="flex items-center gap-1.5"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="ais-robot-glow"></div>
            </div>
        </div>
        <div class="lg:col-span-3 reveal delay-200">
            <div class="ais-card h-full p-6 flex flex-col overflow-hidden relative">
                <div class="absolute inset-x-0 top-0 h-28 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 70% 90% at 50% 0%, rgba(22,139,255,.35), transparent 70%);"></div>
                <div class="mx-auto mt-2 relative" aria-hidden="true">
                    <div class="w-20 h-20 mx-auto flex items-center justify-center" style="background:linear-gradient(180deg, rgba(22,139,255,.35), rgba(121,88,255,.25)); clip-path:polygon(50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%, 0 25%); border:1px solid rgba(0,207,255,.6);">
                        <svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5m0 0l-2.5-2.5M12 13l2.5-2.5"/></svg>
                    </div>
                </div>
                <h3 class="ais-h2 mt-4 text-center text-lg leading-snug">Your Security<br>Our Priority</h3>
                <p class="ais-sub mt-3 text-center text-[12.5px] leading-relaxed">Advanced threat detection, proactive monitoring and protection tailored to your business.</p>
                <a href="{{ $svc('cybersecurity') }}" class="ais-btn-ghost mt-5 justify-center !text-[12.5px]">Explore Security Solutions
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
            </div>
        </div>
    </div>
</section>

{{-- ═════════ STATS STRIP (honest, no fabricated figures) ═════════ --}}
<section class="relative w-full" aria-label="How we work" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pb-2">
        <div class="ais-card reveal px-6 py-5 grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['t' => 'Business-Focused', 'd' => 'Tailored Solutions', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a3 3 0 11-3-3"/>'],
                ['t' => 'Security First', 'd' => 'Protection by Design', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/>'],
                ['t' => '24/7 Options', 'd' => 'Where Contracted', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z"/>'],
                ['t' => 'End-to-End', 'd' => 'Project Support', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.5a.56.56 0 011.04 0l2.13 5.11 5.52.44c.5.04.7.66.32.99l-4.2 3.6 1.28 5.38c.12.5-.42.9-.86.63L12 16.72l-4.71 2.93c-.44.27-.98-.13-.86-.63l1.28-5.38-4.2-3.6c-.38-.33-.18-.95.32-.99l5.52-.44 2.13-5.11z"/>'],
            ] as $i => $s)
            <div class="flex items-center gap-3.5 {{ $i > 0 ? 'lg:border-l lg:border-white/10 lg:pl-6' : '' }}">
                <span class="ais-icon !w-10 !h-10 !rounded-full" style="background:rgba(0,207,255,.12); color:#00CFFF; border-color:rgba(0,207,255,.4);" aria-hidden="true"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $s['icon'] !!}</svg></span>
                <span><strong class="block text-white text-[15px] font-extrabold leading-tight">{{ $s['t'] }}</strong><span class="block text-[12px]" style="color:#B6C8DD;">{{ $s['d'] }}</span></span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ COMPLETE IT & AI SOLUTIONS ═════════ --}}
<section class="relative w-full" aria-labelledby="services-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="reveal">
            <p class="ais-eyebrow">Our Services</p>
            <h2 id="services-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Complete IT &amp; AI Solutions Under One Roof</h2>
            <p class="ais-sub mt-3 text-[13px] max-w-3xl">From infrastructure to intelligent automation, we provide end-to-end services to keep your business secure, productive and ready for the future.</p>
        </div>
        <div class="mt-7 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3.5">
            @foreach([
                ['t' => 'IT Support & Helpdesk', 'd' => 'Fast, reliable support for all your IT needs.', 'u' => $svc('managed-it-support'), 'art' => 'linear-gradient(160deg, rgba(22,139,255,.5), rgba(4,19,38,.9)),repeating-linear-gradient(90deg, rgba(0,207,255,.15) 0 2px, transparent 2px 14px)', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                ['t' => 'Cloud Solutions', 'd' => 'Scalable, secure and high-performance cloud services.', 'u' => $svc('cloud-server-network'), 'art' => 'radial-gradient(circle at 50% 35%, rgba(0,207,255,.55), transparent 60%),linear-gradient(180deg, rgba(22,139,255,.35), rgba(4,19,38,.95))', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/>'],
                ['t' => 'Cybersecurity', 'd' => 'Advanced protection for your digital assets.', 'u' => $svc('cybersecurity'), 'art' => 'radial-gradient(circle at 50% 30%, rgba(0,207,255,.5), transparent 55%),linear-gradient(180deg, rgba(9,30,66,.9), rgba(4,19,38,.95))', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3zM9.2 12l1.9 1.9L15 10"/>'],
                ['t' => 'AI Integration & Agents', 'd' => 'Custom AI agents and automation for your business.', 'u' => route('ai.index'), 'art' => 'radial-gradient(circle at 70% 30%, rgba(121,88,255,.5), transparent 55%),radial-gradient(circle at 30% 60%, rgba(0,207,255,.4), transparent 55%),linear-gradient(180deg, rgba(9,30,66,.9), rgba(4,19,38,.95))', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/>'],
                ['t' => 'Managed Services', 'd' => 'Proactive monitoring, maintenance and support.', 'u' => $svc('managed-it-support'), 'art' => 'linear-gradient(160deg, rgba(121,88,255,.4), rgba(4,19,38,.95)),repeating-linear-gradient(0deg, rgba(0,207,255,.12) 0 2px, transparent 2px 12px)', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13h4l2 3h6l2-3h4M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>'],
            ] as $c)
            <article class="ais-card group overflow-hidden reveal flex flex-col">
                <div class="h-28 relative flex items-center justify-center" style="background:{{ $c['art'] }};" aria-hidden="true">
                    <svg class="w-11 h-11" fill="none" viewBox="0 0 24 24" stroke="#BFE9FF" stroke-width="1.4">{!! $c['icon'] !!}</svg>
                    <div class="absolute inset-0" style="background:linear-gradient(180deg, transparent 30%, rgba(2,9,22,.85));"></div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-[13.5px] font-bold text-white leading-snug">{{ $c['t'] }}</h3>
                    <p class="mt-1.5 text-[12px] leading-relaxed flex-1" style="color:#B6C8DD;">{{ $c['d'] }}</p>
                    <a href="{{ $c['u'] }}" class="ais-link mt-3">Learn More
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ PROCESS ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="process-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-4 reveal">
            <p class="ais-eyebrow">Our Process</p>
            <h2 id="process-heading" class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">Simple Steps to a Smarter, Safer Future</h2>
            <p class="ais-sub mt-4 text-[13px] leading-relaxed">We make it easy to get started. Our proven process ensures you get the right solution, with full support every step of the way.</p>
        </div>
        <ol class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach([
                ['n' => '01', 't' => 'Consultation', 'd' => 'Understand your needs and goals.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/>'],
                ['n' => '02', 't' => 'Strategy', 'd' => 'Design the right solution for your business.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M5 9a8 8 0 0114-3M19 15a8 8 0 01-14 3"/>'],
                ['n' => '03', 't' => 'Implementation', 'd' => 'Build, configure and integrate.', 'icon' => '<circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/>'],
                ['n' => '04', 't' => 'Support', 'd' => 'Ongoing monitoring, updates and assistance.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.3 4.3a1.5 1.5 0 012.4 0l8 10.4a1.5 1.5 0 01-1.2 2.3H4.5a1.5 1.5 0 01-1.2-2.3l8-10.4zM12 10v4m0 3h.01"/>'],
            ] as $i => $s)
            <li class="relative reveal text-center sm:text-left" style="transition-delay:{{ $i * 80 }}ms;">
                @if($i < 3)<span class="hidden xl:block absolute top-[23px] left-[52px] right-[-24px] h-px" style="background:linear-gradient(90deg, rgba(0,207,255,.5), rgba(0,207,255,.08));" aria-hidden="true"></span>@endif
                <span class="ais-step-dot relative" aria-hidden="true"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $s['icon'] !!}</svg></span>
                <p class="mt-3 text-[11px] font-bold tracking-widest" style="color:#00CFFF;">{{ $s['n'] }}</p>
                <h3 class="text-white text-[14px] font-bold">{{ $s['t'] }}</h3>
                <p class="mt-1 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $s['d'] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ═════════ FINAL CTA ═════════ --}}
<section class="relative w-full overflow-hidden border-t border-white/5" aria-labelledby="cta-heading" style="background:linear-gradient(180deg,#03101f,#020916);">
    <svg class="absolute bottom-0 left-0 w-full h-56 opacity-40 pointer-events-none" viewBox="0 0 1200 220" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 160 C 200 120, 350 200, 600 150 S 1000 90, 1200 150" fill="none" stroke="#168BFF" stroke-width="1.5" opacity=".6"/>
        <path d="M0 180 C 220 140, 380 215, 620 168 S 1010 112, 1200 170" fill="none" stroke="#7958FF" stroke-width="1.2" opacity=".55"/>
        <path d="M0 200 C 240 165, 400 225, 640 188 S 1020 140, 1200 190" fill="none" stroke="#00CFFF" stroke-width="1" opacity=".5"/>
    </svg>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-2 gap-8 items-center">
        <div class="reveal">
            <p class="ais-eyebrow">Ready to get started?</p>
            <h2 id="cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Let&rsquo;s Build Your AI-Powered Future</h2>
            <p class="ais-sub mt-3 text-[13px]">Get expert advice, a custom solution and ongoing support — all in one place.</p>
            <div class="mt-6 flex flex-col sm:flex-row flex-wrap gap-3">
                <a href="{{ route('get-quote') }}" class="ais-btn-primary">Get a Free Consultation
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="{{ route('services.index') }}" class="ais-btn-ghost">Explore Our Services</a>
            </div>
        </div>
        <div class="reveal delay-100">
            <div class="ais-dc p-5" role="img" aria-label="Stylised AI data centre with server racks and a central AI core">
                <div class="grid grid-cols-3 gap-3 items-end">
                    @foreach([['h'=>['38%','62%','48%']],['h'=>['55%','42%','66%']],['h'=>['46%','58%','40%']]] as $rack)
                    <div class="rounded-lg p-2" style="background:rgba(7,26,48,.8); border:1px solid rgba(22,139,255,.3);" aria-hidden="true">
                        @foreach($rack['h'] as $h)
                        <div class="rounded-sm my-1.5" style="height:8px; width:{{ $h }}; background:linear-gradient(90deg,#168BFF,#00CFFF); opacity:.8;"></div>
                        @endforeach
                        <div class="rounded-sm my-1.5" style="height:8px; width:70%; background:rgba(121,88,255,.7);"></div>
                    </div>
                    @endforeach
                </div>
                <div class="absolute left-1/2 top-6 -translate-x-1/2 text-center" aria-hidden="true">
                    <div class="w-16 h-16 mx-auto rounded-xl flex items-center justify-center text-white font-extrabold text-lg" style="background:linear-gradient(160deg, rgba(22,139,255,.9), rgba(121,88,255,.85)); border:1px solid rgba(0,207,255,.7); box-shadow:0 0 44px -4px rgba(0,207,255,.75); font-family:Sora,Inter,sans-serif;">AI</div>
                    <div class="mx-auto mt-1 h-10 w-px" style="background:linear-gradient(180deg, rgba(0,207,255,.8), transparent);"></div>
                </div>
                <div class="absolute top-5 left-5 flex items-center gap-1.5 text-[11px] px-2.5 py-1.5 rounded-lg" style="background:rgba(7,26,48,.9); border:1px solid rgba(0,207,255,.4); color:#BFE3FF;" aria-hidden="true">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/></svg>AI Core · Online</div>
                <div class="absolute top-5 right-5 flex items-center gap-1.5 text-[11px] px-2.5 py-1.5 rounded-lg" style="background:rgba(7,26,48,.9); border:1px solid rgba(121,88,255,.5); color:#D9CFFF;" aria-hidden="true">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/></svg>Cloud · Connected</div>
            </div>
        </div>
    </div>
</section>
</div>

@push('head')
<script type="application/ld+json">
{!! json_encode(['@context' => 'https://schema.org', '@type' => 'ProfessionalService', 'name' => 'AISecurity', 'description' => 'Advanced IT support, intelligent AI agents, cybersecurity and business automation.', 'url' => url('/')], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush
@endsection
