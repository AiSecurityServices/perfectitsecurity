@extends('layouts.public')

@section('title', 'AI Agent Services — Design, Deployment & Automation | AISecurity')
@section('description', 'Custom AI agents, platform setup, local and private AI deployment, business automation, AI security, and managed AI support — designed, deployed, and supported by one engineering team.')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    'name' => 'AISecurity — AI Agent Services',
    'description' => 'Custom AI agent development, installation and setup, local and private AI deployment, business automation, AI security, and managed AI support.',
    'url' => route('ai.index'),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="ais-page">

{{-- ═══════════ HERO ═══════════ --}}
<section class="relative w-full overflow-hidden" aria-labelledby="ai-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 lg:pt-20 pb-10 lg:pb-14 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <nav aria-label="Breadcrumb" class="reveal">
                <ol class="ais-crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">AI Agent Services</li>
                </ol>
            </nav>
            <p class="ais-eyebrow reveal mt-5">AI Agents &bull; Automation &bull; Private AI</p>
            <h1 id="ai-hero-heading" class="mt-4 font-extrabold tracking-tight leading-[1.05] text-4xl sm:text-5xl reveal" style="font-family:Sora,Inter,system-ui,sans-serif;">
                <span class="text-white">AI Agents,</span><br>
                <span class="ais-title-hl">Engineered for Business.</span>
            </h1>
            <p class="mt-5 text-[14px] sm:text-[15px] leading-relaxed max-w-xl reveal delay-100" style="color:#B6C8DD;">
                From custom AI agents and intelligent business automation to local, private AI
                deployments — we design, deploy, and support AI that operates inside your
                permissions, your approvals, and your audit trail.
            </p>
            <ul class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 max-w-xl reveal delay-200" aria-label="Service highlights">
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span><strong class="text-white font-semibold">Human approval</strong> gates</span></li>
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/></svg><span><strong class="text-white font-semibold">Authorized scopes</strong> only</span></li>
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/></svg><span><strong class="text-white font-semibold">Full action</strong> logging</span></li>
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z"/></svg><span><strong class="text-white font-semibold">Ticketed &amp; tracked</strong> delivery</span></li>
            </ul>
            <div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3 reveal delay-200">
                <a href="{{ route('get-quote') }}" class="ais-btn-primary">Request an AI Agent Assessment
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="#catalogue" class="ais-btn-ghost">Explore the Catalogue</a>
            </div>
        </div>
        <x-ais-globe :chips="[
            ['<circle cx=\'12\' cy=\'8\' r=\'3.5\'/><path stroke-linecap=\'round\' d=\'M5 20c1.5-3.5 4-5 7-5s5.5 1.5 7 5\'/>', 'AI Agents', 'tl'],
            ['<circle cx=\'12\' cy=\'12\' r=\'3\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 2v3m0 14v3M2 12h3m14 0h3M5 5l2 2m10 10l2 2M19 5l-2 2M7 17l-2 2\'/>', 'Automation', 'tr'],
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud', 'mr'],
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity', 'bc'],
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z\'/>', 'IT Support', 'ml'],
        ]" />
    </div>
</section>

{{-- ═══════════ DIVISIONS ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="divisions-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="divisions-heading" eyebrow="AI Divisions"
            title="Four disciplines. One accountable team."
            sub="Each discipline is delivered through the same ticketed, tracked workflow as the rest of our IT services — scoped, tested, documented, and handed over." />
        @php
            $divPalette = [
                ['bg' => 'rgba(22,139,255,.18)', 'fg' => '#4DA3FF'],
                ['bg' => 'rgba(255,180,84,.16)', 'fg' => '#FFB454'],
                ['bg' => 'rgba(121,88,255,.18)', 'fg' => '#9275FF'],
                ['bg' => 'rgba(0,230,122,.14)', 'fg' => '#00E67A'],
            ];
            $divIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/>';
        @endphp
        <div class="mt-8 grid gap-3.5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($categories as $i => $cat)
            @php $dc = $divPalette[$i % count($divPalette)]; @endphp
            <article class="ais-card group p-4 reveal">
                <div class="flex items-start justify-between gap-2">
                    <span class="ais-icon" style="background:{{ $dc['bg'] }}; color:{{ $dc['fg'] }};" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $divIcon !!}</svg></span>
                    <span class="mt-1 text-[11px] font-bold tracking-[0.18em]" style="color:#7FB7E8;" aria-hidden="true">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h3 class="mt-3 text-[13.5px] font-bold text-white leading-snug">{{ $cat->name }}</h3>
                <p class="mt-1.5 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $cat->description }}</p>
                <p class="mt-3 text-[11.5px] font-semibold" style="color:#7FB7E8;">{{ $cat->services->count() }} service{{ $cat->services->count() === 1 ? '' : 's' }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ CATALOGUE (filterable, real data) ═══════════ --}}
<section id="catalogue" class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="catalogue-heading"
    x-data="{ activeCat: 'all', query: '' }">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="catalogue-heading" eyebrow="Service Catalogue"
            title="Every AI offering, filterable."
            sub="Filter by discipline or search. Every card opens a detailed service page with scope, deliverables, process, and FAQs — and every enquiry flows into our tracked quote workflow." />

        <div class="mt-8 flex flex-col lg:flex-row gap-3 lg:items-center reveal" role="search" aria-label="Filter AI services">
            <div class="flex flex-wrap gap-2" role="group" aria-label="Filter by discipline">
                <button type="button" @click="activeCat = 'all'" :aria-pressed="activeCat === 'all'"
                    :class="activeCat === 'all' ? 'text-white border-[rgba(0,207,255,.65)] bg-[rgba(0,207,255,.14)]' : 'text-[#B6C8DD] border-white/10 hover:border-[rgba(0,207,255,.4)]'"
                    class="cursor-pointer rounded-full border px-3.5 py-1.5 text-[12px] font-semibold transition">All ({{ $allServices->count() }})</button>
                @foreach($categories as $cat)
                <button type="button" @click="activeCat = '{{ $cat->slug }}'" :aria-pressed="activeCat === '{{ $cat->slug }}'"
                    :class="activeCat === '{{ $cat->slug }}' ? 'text-white border-[rgba(0,207,255,.65)] bg-[rgba(0,207,255,.14)]' : 'text-[#B6C8DD] border-white/10 hover:border-[rgba(0,207,255,.4)]'"
                    class="cursor-pointer rounded-full border px-3.5 py-1.5 text-[12px] font-semibold transition">{{ $cat->name }}</button>
                @endforeach
            </div>
            <div class="lg:ml-auto lg:min-w-[280px]">
                <label for="ai-catalogue-search" class="sr-only">Search AI services</label>
                <input id="ai-catalogue-search" x-model="query" type="search" placeholder="Search: e.g. RAG, support, automation…"
                    class="w-full rounded-full border border-white/10 px-4 py-2.5 text-sm text-white placeholder:text-[#7d93ad] focus:border-[rgba(0,207,255,.6)] focus:outline-none" style="background:rgba(7,26,48,.7);" autocomplete="off">
            </div>
        </div>

        @php
            $iconPalette = [
                ['bg' => 'rgba(22,139,255,.18)', 'fg' => '#4DA3FF'],
                ['bg' => 'rgba(255,180,84,.16)', 'fg' => '#FFB454'],
                ['bg' => 'rgba(121,88,255,.18)', 'fg' => '#9275FF'],
                ['bg' => 'rgba(0,230,122,.14)', 'fg' => '#00E67A'],
                ['bg' => 'rgba(0,207,255,.14)', 'fg' => '#00CFFF'],
                ['bg' => 'rgba(255,92,92,.14)', 'fg' => '#FF8A8A'],
            ];
            $sparkIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/>';
        @endphp
        <div class="mt-8 grid gap-3.5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($allServices as $service)
            @php $pc = $iconPalette[$loop->index % count($iconPalette)]; @endphp
            <article class="ais-card group p-5 flex flex-col reveal"
                x-show="(activeCat === 'all' || activeCat === '{{ $service->category->slug }}') && ('{{ strtolower($service->name . ' ' . $service->short_description . ' ' . implode(' ', $service->tags ?? [])) }}'.includes(query.toLowerCase()))"
                x-transition.opacity>
                <div class="flex items-start justify-between gap-2">
                    <span class="ais-icon" style="background:{{ $pc['bg'] }}; color:{{ $pc['fg'] }};" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $sparkIcon !!}</svg></span>
                    <svg class="w-4 h-4 mt-1 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="#7FB7E8" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </div>
                <p class="mt-3 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.14em]" style="color:#00CFFF;">
                    {{ $service->category->name }}
                    @if($service->is_featured)<span class="rounded-full border border-[rgba(0,207,255,.5)] px-2 py-0.5 text-[10px] tracking-[0.12em]">Featured</span>@endif
                </p>
                <h3 class="mt-2 text-[15px] font-bold text-white leading-snug">
                    <a href="{{ route('ai.show', $service->slug) }}">{{ $service->name }}</a>
                </h3>
                <p class="mt-1.5 text-[12.5px] leading-relaxed flex-1" style="color:#B6C8DD;">{{ $service->short_description }}</p>
                @if(!empty($service->tags))
                <ul class="mt-3 flex flex-wrap gap-1.5" aria-label="Topics">
                    @foreach(array_slice($service->tags, 0, 4) as $tag)
                    <li class="text-[11px]" style="color:#7FB7E8;">#{{ $tag }}</li>
                    @endforeach
                </ul>
                @endif
                <div class="mt-4 pt-4 border-t border-white/5 flex flex-wrap items-center gap-x-5 gap-y-2">
                    <a href="{{ route('ai.show', $service->slug) }}" class="ais-link">Service Details
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                    <a href="{{ route('get-quote', ['service_id' => $service->id]) }}" class="ais-link">Request Proposal
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                </div>
            </article>
            @endforeach
        </div>
        <p class="mt-6 text-[11px] tracking-wider" style="color:#7d93ad;" aria-live="polite"
            x-text="'Showing ' + $el.parentElement.querySelectorAll('article[x-show]').length + ' of {{ $allServices->count() }} services'">Showing {{ $allServices->count() }} of {{ $allServices->count() }} services</p>
        <noscript><p class="mt-4 text-sm" style="color:#B6C8DD;">Filtering needs JavaScript; all {{ $allServices->count() }} services are listed above.</p></noscript>
    </div>
</section>

{{-- ═══════════ PROCESS ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="ai-process-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="ai-process-heading" eyebrow="Delivery Process"
            title="From discovery to ongoing support."
            sub="The same seven-stage delivery spine runs under every AI engagement — adapted per service, always documented." />
        <ol class="mt-8 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['01', 'DISCOVER', 'Map the task, data, systems, and decision points with your team.'],
                ['02', 'ASSESS', 'Verify models, tools, hardware, licenses, and constraints before committing.'],
                ['03', 'DESIGN', 'Define responsibilities, permissions, integrations, and approval gates.'],
                ['04', 'BUILD', 'Configure and develop in a non-production environment first.'],
                ['05', 'TEST & REVIEW', 'Accuracy checks, permission review, and security assessment.'],
                ['06', 'DEPLOY', 'Production rollout with documentation and team training.'],
                ['07', 'SUPPORT', 'Monitoring guidance and optional maintenance agreements.'],
            ] as $s)
            <li class="ais-card p-5 reveal">
                <div class="text-[11px] font-bold tracking-[0.22em]" style="color:#00CFFF;">{{ $s[0] }}</div>
                <h3 class="mt-2 text-[13.5px] font-bold tracking-tight text-white">{{ $s[1] }}</h3>
                <p class="mt-2 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">{{ $s[2] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ═══════════ ENGAGEMENT ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="engagement-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="engagement-heading" eyebrow="Engagement Models"
            title="Clear engagement models. Honest scoping."
            sub="We scope each project individually and propose in writing. No fixed prices are published because every environment differs — request a custom proposal and receive itemized scope, exclusions, and next steps." />
        <div class="mt-8 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['TECHNICAL ASSESSMENT', 'Feasibility, compatibility, and risk review with written findings — the right start when you are unsure what is possible.'],
                ['ONE-TIME SETUP', 'Installation and configuration of an approved platform or environment, tested and documented.'],
                ['INTEGRATION PROJECT', 'Custom agent or automation build connecting your tools, data, and approvals.'],
                ['MANAGED AI SUPPORT', 'Ongoing health checks, updates, cost reviews, and troubleshooting under a defined scope.'],
            ] as $e)
            <div class="ais-card p-5 reveal">
                <h3 class="text-[12px] font-bold tracking-[0.14em] text-white">{{ $e[0] }}</h3>
                <p class="mt-3 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">{{ $e[1] }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-8 reveal">
            <a href="{{ route('get-quote') }}" class="ais-btn-primary">Request a Custom Proposal
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        </div>
    </div>
</section>

{{-- ═══════════ SAFETY ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="safety-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-5">
            <x-ais-section-head id="safety-heading" eyebrow="Safety Principles"
                title="Powerful, but never unsupervised."
                sub="Our safety posture is architectural, not cosmetic — enforced in configuration, permissions, and process." />
        </div>
        <div class="lg:col-span-7 ais-card p-6 sm:p-7 reveal">
            <ul class="space-y-3.5 text-sm">
                @foreach([
                    'Human approval gates on consequential or irreversible actions',
                    'Least-privilege tool permissions, reviewed before deployment',
                    'Work inside explicitly authorized scopes — nothing more',
                    'Full audit logging of agent actions and decisions',
                    'Secrets kept in your systems, never in prompts or repos',
                    'Local inference improves control — we still configure access, logging, and isolation',
                ] as $rule)
                <li class="flex items-start gap-3" style="color:#DCEBFF;">
                    <span class="mt-0.5 inline-flex w-[18px] h-[18px] rounded-full items-center justify-center flex-shrink-0" style="background:rgba(0,207,255,.15); border:1px solid rgba(0,207,255,.5);" aria-hidden="true"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>{{ $rule }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ═══════════ FAQ ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="ai-faq-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="ai-faq-heading" eyebrow="FAQ"
            title="Questions we hear before every AI project."
            sub="Straight answers — including the limits, not just the possibilities." />
        <div class="mt-8 grid gap-3.5 lg:grid-cols-2 max-w-5xl">
            @foreach([
                ['Do we need technical staff to run an AI agent?', 'No. We handle installation, configuration, and documentation, and train your team on daily operation. Managed support covers the rest.'],
                ['Which AI platforms can you work with?', 'Open-source agent frameworks, Claude Code workflows, major coding assistants, Ollama-compatible local models, and approved cloud platforms — each verified during assessment for your use case.'],
                ['Will our data leave our organization?', 'Only where you approve it. Cloud models process data externally by nature; local deployments keep inference inside your infrastructure. We document the data path for every design.'],
                ['How long does a typical project take?', 'Platform setups take days; custom agents and automations typically take two to six weeks including testing. The assessment gives you a written timeline.'],
                ['What does it cost?', 'Every environment differs, so we quote per project after assessment. Use the custom proposal workflow — scope, exclusions, and next steps arrive in writing.'],
                ['Can AI replace our support or sales staff?', 'It removes repetitive load so staff focus on judgment work. Uncertain cases escalate to humans by design — augmentation, not replacement.'],
            ] as $f)
            <details class="ais-card p-5 group reveal">
                <summary class="cursor-pointer text-[14px] font-bold tracking-tight text-white list-none flex items-center justify-between gap-4">
                    {{ $f[0] }}
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <p class="mt-3 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">{{ $f[1] }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ CTA ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="ai-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-12 lg:py-16 text-center">
        <p class="ais-eyebrow reveal">Assessment</p>
        <h2 id="ai-cta-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl text-balance reveal">Let&rsquo;s find your highest-value AI workflow.</h2>
        <p class="ais-sub mt-4 text-[13.5px] max-w-xl mx-auto reveal delay-100">Start with an assessment: we map your workflows, verify feasibility, and propose in writing. Enquiries land in the same tracked queue as all our work.</p>
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3 reveal delay-200">
            <a href="{{ route('get-quote') }}" class="ais-btn-primary">Request an AI Agent Assessment
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
            <a href="{{ route('contact') }}" class="ais-btn-ghost">Discuss a Project</a>
        </div>
    </div>
</section>

</div>
@endsection
