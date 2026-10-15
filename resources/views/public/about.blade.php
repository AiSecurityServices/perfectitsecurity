@extends('layouts.public')

@section('title', 'About Us — AISecurity')
@section('description', 'Enterprise IT infrastructure, cybersecurity, and cloud engineering company. Our team delivers technology solutions for organizations worldwide.')

@section('content')
<div class="ais-page">

<x-ais-page-hero
    eyebrow="About AISecurity"
    title="We Engineer the <span class='ais-title-hl'>Backbone of Modern Business</span>"
    lede="AISecurity delivers enterprise-grade IT infrastructure, predictive cybersecurity, and cloud engineering. We are trusted by organizations that demand continuous reliability, rigorous security compliance, and uncompromising operational uptime."
    :chips="[
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z\'/>', 'AI Agents'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z\'/>', '24/7 Support'],
    ]"
>
    <x-slot:breadcrumbs>
        <nav class="ais-crumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">About</span></nav>
    </x-slot:breadcrumbs>
    <x-slot:actions>
        <a href="{{ route('contact') }}" class="ais-btn-primary">Talk to an Expert
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        <a href="{{ route('services.index') }}" class="ais-btn-ghost">Explore Our Services</a>
    </x-slot:actions>
</x-ais-page-hero>

{{-- ═════════ MISSION & VISION ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-label="Mission and vision" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head eyebrow="Who We Are" title="Directive and long-term horizon." sub="What the team is mandated to deliver, every engagement." />
        <div class="mt-7 grid lg:grid-cols-2 gap-3.5">
            <div class="ais-card p-7 sm:p-8 reveal">
                <p class="ais-eyebrow">Our Mission</p>
                <p class="mt-3 text-[13.5px] leading-relaxed" style="color:#DCEBFF;">
                    We believe technology should be an absolute competitive accelerator, never an operational bottleneck. Our mission is to engineer enterprise IT environments that are self-healing, bulletproof against ransomware, and meticulously aligned with your strategic business trajectory.
                </p>
            </div>
            <div class="ais-card p-7 sm:p-8 reveal delay-100">
                <p class="ais-eyebrow">Our Vision</p>
                <p class="mt-3 text-[13.5px] leading-relaxed" style="color:#DCEBFF;">
                    To be the foremost technology and cyber protection ally for forward-thinking enterprises. We establish lifelong corporate partnerships through uncompromising engineering transparency, rapid SLA adherence, and proactive zero-trust defense frameworks.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═════════ CAPABILITIES — WHY AISECURITY ROW ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="infra-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-6 items-stretch">
        <div class="lg:col-span-4 reveal">
            <p class="ais-eyebrow">Our Capabilities</p>
            <h2 id="infra-heading" class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">Architected for resilience at every digital layer.</h2>
            <p class="ais-sub mt-4 text-[13px] leading-relaxed">Our Global Security Operations Center (SOC) operates 24/7/365 with active telemetry analysis, automated containment playbooks, and redundant cloud clusters across tier-IV certified data centers worldwide.</p>
            <ul class="mt-5 space-y-2.5 text-[13px]" style="color:#DCEBFF;">
                @foreach([
                    'Tier IV certified data centers with N+2 power, cooling, and network redundancy',
                    'Real-time automated heuristic telemetry across all server clusters and cloud endpoints',
                    'ISO 27001, SOC 2 Type II, HIPAA, and GDPR aligned security governance',
                    'Automated micro-segmentation and instantaneous ransomware rollback mechanisms'
                ] as $point)
                <li class="flex items-start gap-2.5"><span class="mt-0.5 inline-flex w-[18px] h-[18px] rounded-full items-center justify-center flex-shrink-0" style="background:rgba(0,207,255,.15); border:1px solid rgba(0,207,255,.5);" aria-hidden="true"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>{{ $point }}</li>
                @endforeach
            </ul>
            <a href="{{ route('contact') }}" class="ais-btn-ghost mt-6 !py-2 !text-[12.5px]">Review Your Architecture
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        </div>
        <div class="lg:col-span-5 reveal delay-100">
            <div class="relative h-full">
                <x-ais-globe static :chips="[
                    ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity', 'tl'],
                    ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud', 'tr'],
                    ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z\'/>', '24/7 Support', 'br'],
                ]" />
                <div class="absolute top-4 right-4 w-[168px] rounded-xl p-3.5 text-left" style="background:linear-gradient(180deg, rgba(13,32,58,.92), rgba(7,26,48,.92)); border:1px solid rgba(0,207,255,.45); box-shadow:0 14px 40px -12px rgba(0,207,255,.4);" aria-hidden="true">
                    <p class="text-[12px] font-extrabold text-white leading-tight">AI Powered<br>IT Support</p>
                    <ul class="mt-2.5 space-y-1.5 text-[11.5px]" style="color:#B6C8DD;">
                        @foreach(['Smarter','Faster','Safer','Always On'] as $w)
                        <li class="flex items-center gap-1.5"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>
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
                <a href="{{ route('services.index', ['category' => 'cybersecurity']) }}" class="ais-btn-ghost mt-5 justify-center !text-[12.5px]">Explore Security Solutions
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
            </div>
        </div>
    </div>
</section>

{{-- ═════════ STATS STRIP (honest, no fabricated figures) ═════════ --}}
<x-ais-stats-strip :items="[
    ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a3 3 0 11-3-3\'/>', 'Business-Focused', 'Tailored Solutions'],
    ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Security First', 'Protection by Design'],
    ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M11.48 3.5a.56.56 0 011.04 0l2.13 5.11 5.52.44c.5.04.7.66.32.99l-4.2 3.6 1.28 5.38c.12.5-.42.9-.86.63L12 16.72l-4.71 2.93c-.44.27-.98-.13-.86-.63l1.28-5.38-4.2-3.6c-.38-.33-.18-.95.32-.99l5.52-.44 2.13-5.11z\'/>', 'End-to-End', 'Project Support'],
]" />

{{-- ═════════ CORE VALUES ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="ethos-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="ethos-heading" eyebrow="Our Ethos" title="Our engineering ethos." sub="Guiding principles behind every work order." />
        <div class="mt-7 grid sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            @foreach([
                ['title' => 'Integrity & Transparency', 'desc' => 'Every invoice, quotation milestone, and SLA report is open, verifiable, and free of hidden costs.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['title' => 'Technical Excellence', 'desc' => 'Certified architects across Microsoft Azure, AWS, Cisco, and offensive cybersecurity standards.', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['title' => 'Proactive Defense', 'desc' => 'We prevent threats before they materialize rather than simply reacting to downtime disruptions.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['title' => 'Relentless Client Focus', 'desc' => 'Your business uptime is our primary KPI. We stand beside your team around the clock, every day.', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $val)
            <div class="ais-card p-6 reveal">
                <span class="ais-icon" style="background:rgba(0,207,255,.12); color:#00CFFF; border-color:rgba(0,207,255,.4);" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $val['icon'] }}"/></svg></span>
                <h3 class="mt-4 text-[14px] font-bold text-white leading-snug">{{ $val['title'] }}</h3>
                <p class="mt-1.5 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">{{ $val['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ CTA ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="about-cta-heading" style="background:#020916;">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 text-center">
        <p class="ais-eyebrow">Work With Us</p>
        <h2 id="about-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Work with certified infrastructure specialists</h2>
        <p class="ais-sub mt-3 text-[13px] max-w-2xl mx-auto">Get in touch with our solutions engineering team to review your current architecture and security posture.</p>
        <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('contact') }}" class="ais-btn-primary">Contact Our Engineering Team
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
            <a href="{{ route('get-quote') }}" class="ais-btn-ghost">Get a Free Quote</a>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
