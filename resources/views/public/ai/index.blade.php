@extends('layouts.public')

@section('title', 'AI Agent Services — Design, Deployment & Automation | PerfectITSecurity')
@section('description', 'Custom AI agents, platform setup, local and private AI deployment, business automation, AI security, and managed AI support — designed, deployed, and supported by one engineering team.')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    'name' => 'PerfectITSecurity — AI Agent Services',
    'description' => 'Custom AI agent development, installation and setup, local and private AI deployment, business automation, AI security, and managed AI support.',
    'url' => route('ai.index'),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- ═══════════ HERO — AI://DIVISION ═══════════ --}}
<section class="premium-hero relative w-full overflow-hidden" aria-labelledby="ai-hero-heading">
    <div class="absolute inset-0 bg-cyber-grid opacity-60 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#55E6FF]/40 to-transparent" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-24 sm:pt-28 lg:pt-32 pb-12 lg:pb-16">
        <nav aria-label="Breadcrumb" class="reveal">
            <ol class="flex flex-wrap items-center gap-2 font-mono text-[11px] tracking-[0.18em] uppercase text-term-700">
                <li><a href="{{ route('home') }}" class="hover:text-accent-soft transition-colors">Home</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-accent-soft">AI Agent Services</li>
            </ol>
        </nav>
        <div class="mt-6 max-w-4xl">
            <p class="reveal"><span class="premium-eyebrow">AI Agents • Automation • Private AI</span></p>
            <h1 id="ai-hero-heading" class="mt-6 font-display font-extrabold tracking-tight leading-[1.02] text-4xl sm:text-6xl lg:text-7xl text-balance reveal">
                <span class="premium-title">AI Agents, Engineered for Business.</span>
            </h1>
            <p class="premium-lede mt-6 text-base sm:text-lg lg:text-xl leading-relaxed max-w-3xl reveal delay-100">
                From custom AI agents and intelligent business automation to local, private AI
                deployments — we design, deploy, and support AI that operates inside your
                permissions, your approvals, and your audit trail.
            </p>
            <div class="mt-9 flex flex-col sm:flex-row flex-wrap gap-3 reveal delay-200">
                <a href="{{ route('get-quote') }}" class="term-btn term-btn-lg btn-premium">
                    Request an AI Agent Assessment
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="#catalogue" class="term-btn term-btn-lg term-btn-ghost btn-ghost-premium">Explore the Catalogue</a>
            </div>
            <p class="mt-5 font-mono text-[11px] tracking-[0.18em] uppercase text-slate-500 dark:text-term-700 reveal delay-300">
                Human approval gates <span class="text-accent mx-1" aria-hidden="true">•</span> Authorized scopes only <span class="text-accent mx-1" aria-hidden="true">•</span> Full action logging
            </p>
        </div>
    </div>
</section>

{{-- ═══════════ 01 / DIVISIONS ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24" aria-labelledby="divisions-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="01" label="AI://DIVISIONS"
            title="Four disciplines. One accountable team."
            desc="Each discipline is delivered through the same ticketed, tracked workflow as the rest of our IT services — scoped, tested, documented, and handed over." />
        <span id="divisions-heading" class="sr-only">AI service disciplines</span>
        <div class="mt-10 grid gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach($categories as $i => $cat)
            <article class="term-panel p-6 group reveal">
                <div class="font-mono text-[11px] tracking-[0.24em] text-accent-soft mb-4">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }} / {{ strtoupper($cat->slug) }}</div>
                <h3 class="font-display text-xl font-bold tracking-tight text-navy-900 dark:text-white">{{ $cat->name }}</h3>
                <p class="mt-2.5 text-sm leading-relaxed text-surface-600 dark:text-term-800">{{ $cat->description }}</p>
                <p class="mt-4 font-mono text-[11px] tracking-wider text-term-700">{{ $cat->services->count() }} service{{ $cat->services->count() === 1 ? '' : 's' }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ 02 / CATALOGUE (filterable, real data) ═══════════ --}}
<section id="catalogue" class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="catalogue-heading"
    x-data="{ activeCat: 'all', query: '' }">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="02" label="AI://CATALOGUE"
            title="Every AI offering, filterable."
            desc="Filter by discipline or search. Every card opens a detailed service page with scope, deliverables, process, and FAQs — and every enquiry flows into our tracked quote workflow." />
        <span id="catalogue-heading" class="sr-only">AI services catalogue</span>

        <div class="mt-8 flex flex-col lg:flex-row gap-3 lg:items-center reveal" role="search" aria-label="Filter AI services">
            <div class="flex flex-wrap gap-2" role="group" aria-label="Filter by discipline">
                <button type="button" @click="activeCat = 'all'" :aria-pressed="activeCat === 'all'"
                    :class="activeCat === 'all' ? 'term-tag term-tag-accent' : 'term-tag'"
                    class="cursor-pointer">All ({{ $allServices->count() }})</button>
                @foreach($categories as $cat)
                <button type="button" @click="activeCat = '{{ $cat->slug }}'" :aria-pressed="activeCat === '{{ $cat->slug }}'"
                    :class="activeCat === '{{ $cat->slug }}' ? 'term-tag term-tag-accent' : 'term-tag'"
                    class="cursor-pointer">{{ $cat->name }}</button>
                @endforeach
            </div>
            <div class="lg:ml-auto lg:min-w-[280px]">
                <label for="ai-catalogue-search" class="sr-only">Search AI services</label>
                <input id="ai-catalogue-search" x-model="query" type="search" placeholder="search: e.g. RAG, support, automation…"
                    class="form-input w-full font-mono text-sm" autocomplete="off">
            </div>
        </div>

        <div class="mt-8 grid gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($allServices as $service)
            <article class="term-panel p-6 group flex flex-col reveal"
                x-show="(activeCat === 'all' || activeCat === '{{ $service->category->slug }}') && ('{{ strtolower($service->name . ' ' . $service->short_description . ' ' . implode(' ', $service->tags ?? [])) }}'.includes(query.toLowerCase()))"
                x-transition.opacity>
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="term-tag">{{ $service->category->name }}</span>
                    @if($service->is_featured)<span class="term-tag term-tag-accent">Featured</span>@endif
                </div>
                <h3 class="font-display text-lg font-bold tracking-tight text-navy-900 dark:text-white group-hover:text-accent-soft transition-colors">
                    <a href="{{ route('ai.show', $service->slug) }}">{{ $service->name }}</a>
                </h3>
                <p class="mt-2 text-sm leading-relaxed text-surface-600 dark:text-term-800 flex-1">{{ $service->short_description }}</p>
                @if(!empty($service->tags))
                <ul class="mt-4 flex flex-wrap gap-1.5" aria-label="Topics">
                    @foreach(array_slice($service->tags, 0, 4) as $tag)
                    <li class="font-mono text-[10px] tracking-wider text-term-700">#{{ $tag }}</li>
                    @endforeach
                </ul>
                @endif
                <div class="mt-5 pt-4 border-t border-term-300 dark:border-white/5 flex flex-wrap items-center gap-x-5 gap-y-2">
                    <a href="{{ route('ai.show', $service->slug) }}" class="term-link">Service Details
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                    <a href="{{ route('get-quote', ['service_id' => $service->id]) }}" class="term-link">Request Proposal →</a>
                </div>
            </article>
            @endforeach
        </div>
        <p class="mt-6 font-mono text-[11px] tracking-wider text-term-700" aria-live="polite"
            x-text="'Showing ' + $el.parentElement.querySelectorAll('article[x-show]').length + ' of {{ $allServices->count() }} services'">Showing {{ $allServices->count() }} of {{ $allServices->count() }} services</p>
        <noscript><p class="mt-4 text-sm text-term-700">Filtering needs JavaScript; all {{ $allServices->count() }} services are listed above.</p></noscript>
    </div>
</section>

{{-- ═══════════ 03 / PROCESS ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="ai-process-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="03" label="AI://PROCESS"
            title="From discovery to ongoing support."
            desc="The same seven-stage delivery spine runs under every AI engagement — adapted per service, always documented." />
        <span id="ai-process-heading" class="sr-only">AI delivery process</span>
        <ol class="mt-10 grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['01', 'DISCOVER', 'Map the task, data, systems, and decision points with your team.'],
                ['02', 'ASSESS', 'Verify models, tools, hardware, licenses, and constraints before committing.'],
                ['03', 'DESIGN', 'Define responsibilities, permissions, integrations, and approval gates.'],
                ['04', 'BUILD', 'Configure and develop in a non-production environment first.'],
                ['05', 'TEST & REVIEW', 'Accuracy checks, permission review, and security assessment.'],
                ['06', 'DEPLOY', 'Production rollout with documentation and team training.'],
                ['07', 'SUPPORT', 'Monitoring guidance and optional maintenance agreements.'],
            ] as $s)
            <li class="term-panel-2 p-5 sm:p-6 reveal">
                <div class="font-mono text-[11px] tracking-[0.24em] text-accent-soft">{{ $s[0] }}</div>
                <h3 class="mt-2 font-display text-base font-bold tracking-tight text-navy-900 dark:text-white">{{ $s[1] }}</h3>
                <p class="mt-2 text-[13px] leading-relaxed text-surface-600 dark:text-term-800">{{ $s[2] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ═══════════ 04 / ENGAGEMENT ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="engagement-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="04" label="AI://ENGAGEMENT"
            title="Clear engagement models. Honest scoping."
            desc="We scope each project individually and propose in writing. No fixed prices are published because every environment differs — request a custom proposal and receive itemized scope, exclusions, and next steps." />
        <span id="engagement-heading" class="sr-only">Engagement models</span>
        <div class="mt-10 grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['TECHNICAL ASSESSMENT', 'Feasibility, compatibility, and risk review with written findings — the right start when you are unsure what is possible.'],
                ['ONE-TIME SETUP', 'Installation and configuration of an approved platform or environment, tested and documented.'],
                ['INTEGRATION PROJECT', 'Custom agent or automation build connecting your tools, data, and approvals.'],
                ['MANAGED AI SUPPORT', 'Ongoing health checks, updates, cost reviews, and troubleshooting under a defined scope.'],
            ] as $e)
            <div class="term-panel p-6 reveal">
                <h3 class="font-mono text-xs tracking-[0.18em] text-navy-900 dark:text-white">{{ $e[0] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-surface-600 dark:text-term-800">{{ $e[1] }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-8 reveal">
            <a href="{{ route('get-quote') }}" class="term-btn term-btn-lg btn-premium">Request a Custom Proposal
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        </div>
    </div>
</section>

{{-- ═══════════ 05 / SAFETY ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="safety-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-7">
            <x-section-head num="05" label="AI://SAFETY"
                title="Powerful, but never unsupervised."
                desc="Our safety posture is architectural, not cosmetic — enforced in configuration, permissions, and process." />
            <span id="safety-heading" class="sr-only">AI safety principles</span>
        </div>
        <div class="lg:col-span-5 term-panel p-6 sm:p-7 reveal">
            <ul class="space-y-3.5 text-sm">
                @foreach([
                    'Human approval gates on consequential or irreversible actions',
                    'Least-privilege tool permissions, reviewed before deployment',
                    'Work inside explicitly authorized scopes — nothing more',
                    'Full audit logging of agent actions and decisions',
                    'Secrets kept in your systems, never in prompts or repos',
                    'Local inference improves control — we still configure access, logging, and isolation',
                ] as $rule)
                <li class="flex items-start gap-3 text-navy-800 dark:text-term-900">
                    <span class="w-1.5 h-1.5 bg-accent flex-shrink-0 mt-1.5" aria-hidden="true"></span>{{ $rule }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ═══════════ 06 / FAQ ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="ai-faq-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="06" label="AI://FAQ"
            title="Questions we hear before every AI project."
            desc="Straight answers — including the limits, not just the possibilities." />
        <span id="ai-faq-heading" class="sr-only">Frequently asked questions</span>
        <div class="mt-10 grid gap-4 sm:gap-5 lg:grid-cols-2 max-w-5xl">
            @foreach([
                ['Do we need technical staff to run an AI agent?', 'No. We handle installation, configuration, and documentation, and train your team on daily operation. Managed support covers the rest.'],
                ['Which AI platforms can you work with?', 'Open-source agent frameworks, Claude Code workflows, major coding assistants, Ollama-compatible local models, and approved cloud platforms — each verified during assessment for your use case.'],
                ['Will our data leave our organization?', 'Only where you approve it. Cloud models process data externally by nature; local deployments keep inference inside your infrastructure. We document the data path for every design.'],
                ['How long does a typical project take?', 'Platform setups take days; custom agents and automations typically take two to six weeks including testing. The assessment gives you a written timeline.'],
                ['What does it cost?', 'Every environment differs, so we quote per project after assessment. Use the custom proposal workflow — scope, exclusions, and next steps arrive in writing.'],
                ['Can AI replace our support or sales staff?', 'It removes repetitive load so staff focus on judgment work. Uncertain cases escalate to humans by design — augmentation, not replacement.'],
            ] as $f)
            <details class="term-panel p-5 sm:p-6 group reveal">
                <summary class="cursor-pointer font-display text-base font-bold tracking-tight text-navy-900 dark:text-white list-none flex items-center justify-between gap-4">
                    {{ $f[0] }}
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <p class="mt-3 text-sm leading-relaxed text-surface-600 dark:text-term-800">{{ $f[1] }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ 07 / CTA ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-28 border-t border-term-300 dark:border-white/5" aria-labelledby="ai-cta-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 text-center">
        <div class="term-sec-head justify-center" aria-hidden="true">
            <span class="term-sec-num">07</span>
            <span class="term-sec-label">AI://ASSESSMENT</span>
        </div>
        <h2 id="ai-cta-heading" class="term-sec-title text-3xl sm:text-4xl lg:text-6xl text-balance reveal">LET'S FIND YOUR<br>HIGHEST-VALUE AI WORKFLOW.</h2>
        <p class="term-sec-desc mt-5 max-w-xl mx-auto reveal delay-100">Start with an assessment: we map your workflows, verify feasibility, and propose in writing. Enquiries land in the same tracked queue as all our work.</p>
        <div class="mt-9 flex flex-col sm:flex-row justify-center gap-3 reveal delay-200">
            <a href="{{ route('get-quote') }}" class="term-btn term-btn-lg btn-premium">Request an AI Agent Assessment
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
            <a href="{{ route('contact') }}" class="term-btn term-btn-lg term-btn-ghost btn-ghost-premium">Discuss a Project →</a>
        </div>
    </div>
</section>

@endsection
