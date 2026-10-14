@extends('layouts.public')

@section('title', ($service->seo_title ?: $service->name . ' | PerfectITSecurity'))
@section('description', ($service->seo_description ?: $service->short_description))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $service->name,
    'description' => $service->short_description,
    'url' => route('ai.show', $service->slug),
    'provider' => ['@type' => 'Organization', 'name' => 'PerfectITSecurity', 'url' => url('/')],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- ═══════════ HERO ═══════════ --}}
<section class="premium-hero relative w-full overflow-hidden" aria-labelledby="service-heading">
    <div class="absolute inset-0 bg-cyber-grid opacity-60 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#55E6FF]/40 to-transparent" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-24 sm:pt-28 lg:pt-32 pb-12 lg:pb-16">
        <nav aria-label="Breadcrumb" class="reveal">
            <ol class="flex flex-wrap items-center gap-2 font-mono text-[11px] tracking-[0.18em] uppercase text-term-700">
                <li><a href="{{ route('home') }}" class="hover:text-accent-soft transition-colors">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('ai.index') }}" class="hover:text-accent-soft transition-colors">AI Agent Services</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-accent-soft">{{ $service->name }}</li>
            </ol>
        </nav>
        <div class="mt-6 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 max-w-4xl">
                <p class="reveal"><span class="premium-eyebrow">{{ $service->category->name }}</span></p>
                <h1 id="service-heading" class="mt-6 font-display font-extrabold tracking-tight leading-[1.02] text-4xl sm:text-5xl lg:text-6xl text-balance reveal">
                    <span class="premium-title">{{ $service->name }}</span>
                </h1>
                <p class="premium-lede mt-6 text-base sm:text-lg leading-relaxed max-w-3xl reveal delay-100">{{ $service->short_description }}</p>
                <div class="mt-8 flex flex-col sm:flex-row flex-wrap gap-3 reveal delay-200">
                    <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}" class="term-btn term-btn-lg btn-premium">
                        Request a Custom Proposal
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="term-btn term-btn-lg term-btn-ghost btn-ghost-premium">Discuss This Service →</a>
                </div>
            </div>
            <div class="lg:col-span-4 term-panel p-6 reveal delay-100" aria-label="Service facts">
                <dl class="space-y-4 font-mono text-xs tracking-wider">
                    <div class="flex justify-between gap-4"><dt class="text-term-700">PRICING</dt><dd class="text-accent-soft text-right">Custom Proposal</dd></div>
                    @if($service->estimated_completion)
                    <div class="flex justify-between gap-4"><dt class="text-term-700">DELIVERY</dt><dd class="text-term-950 dark:text-term-950 text-right">{{ $service->estimated_completion }}</dd></div>
                    @endif
                    <div class="flex justify-between gap-4"><dt class="text-term-700">DIVISION</dt><dd class="text-term-950 dark:text-term-950 text-right">{{ $service->category->name }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-term-700">SUPPORT</dt><dd class="text-term-950 dark:text-term-950 text-right">Ticketed &amp; tracked</dd></div>
                </dl>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ 01 / PROBLEM & SOLUTION ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24" aria-labelledby="problem-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-7">
            <x-section-head num="01" label="AI://SOLUTION"
                title="The problem this solves."
                desc="Concrete operational pain — and exactly how this service addresses it." />
            <span id="problem-heading" class="sr-only">Problem and solution</span>
            <p class="mt-8 text-base sm:text-lg leading-relaxed text-surface-600 dark:text-term-800 reveal">{{ $service->description }}</p>
        </div>
        <div class="lg:col-span-5 flex flex-col gap-4">
            <div class="term-panel p-6 reveal">
                <div class="font-mono text-[10px] tracking-[0.24em] text-accent-soft uppercase mb-3">IN SCOPE</div>
                <p class="text-sm leading-relaxed text-surface-600 dark:text-term-800">{{ $service->scope }}</p>
            </div>
            @if($service->exclusions)
            <div class="term-panel-2 p-6 reveal">
                <div class="font-mono text-[10px] tracking-[0.24em] text-term-700 uppercase mb-3">EXCLUDED — BY DESIGN</div>
                <p class="text-sm leading-relaxed text-term-700 dark:text-term-800">{{ $service->exclusions }}</p>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══════════ 02 / CAPABILITIES ═══════════ --}}
@if(!empty($service->features) && count($service->features) > 0)
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="capabilities-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="02" label="AI://CAPABILITIES"
            title="What this service includes."
            desc="Each capability is configured for your environment — never a generic install-and-leave." />
        <span id="capabilities-heading" class="sr-only">Capabilities</span>
        <div class="mt-10 grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($service->features as $i => $feature)
            <div class="term-panel p-6 group reveal">
                <div class="font-mono text-[11px] tracking-[0.24em] text-accent-soft">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }}</div>
                <p class="mt-3 text-sm leading-relaxed text-navy-900 dark:text-term-950">{{ $feature }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ 03 / INTERACTIVE WORKFLOW VISUALIZATION ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="workflow-heading"
    x-data="{ step: 3, steps: [
        { t: 'Trigger', d: 'A defined event starts the workflow — a new enquiry, a schedule, or a human request. Nothing runs uninvited.' },
        { t: 'Input', d: 'Only the data the task needs is collected from approved sources — inboxes, documents, CRM, or your knowledge base.' },
        { t: 'AI Processing', d: 'The model reasons over the inputs within its instructions. Capabilities depend on the selected model and configuration.' },
        { t: 'Tools', d: 'The agent may call approved integrations via official APIs — search, tickets, calendars, documents. Each tool is permissioned.' },
        { t: 'Human Approval', d: 'Consequential or irreversible actions pause here for a person to review and authorize. Low-risk steps may proceed per policy.' },
        { t: 'Action', d: 'The approved action executes — a draft created, a record updated, a message queued for sending.' },
        { t: 'Log', d: 'Every step is written to an audit log: what ran, what was approved, and what changed. Failures alert, never vanish.' }
    ] }">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="03" label="AI://WORKFLOW"
            title="How an agent workflow actually runs."
            desc="Select any stage to inspect it. This is the documented pattern behind every automation we ship — trigger to log." />
        <span id="workflow-heading" class="sr-only">Interactive workflow diagram</span>
        <div class="mt-10 term-panel p-6 sm:p-8 reveal">
            <div class="flex flex-wrap gap-2" role="group" aria-label="Workflow stages">
                <template x-for="(s, i) in steps" :key="i">
                    <button type="button" @click="step = i" :aria-pressed="step === i"
                        :class="step === i ? 'term-tag term-tag-accent' : 'term-tag'" class="cursor-pointer"
                        x-text="'0' + (i + 1) + ' · ' + s.t"></button>
                </template>
            </div>
            <div class="mt-6 flex flex-col lg:flex-row gap-6 items-start">
                <svg viewBox="0 0 640 120" class="w-full lg:max-w-xl flex-shrink-0" role="img" aria-label="Agent workflow pipeline diagram">
                    <defs>
                        <linearGradient id="wfline" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0" stop-color="#55E6FF"/><stop offset="1" stop-color="#9275FF"/>
                        </linearGradient>
                    </defs>
                    <line x1="20" y1="60" x2="620" y2="60" stroke="url(#wfline)" stroke-width="2" opacity="0.5"/>
                    <template x-for="(s, i) in steps" :key="'n' + i">
                        <g :transform="'translate(' + (20 + i * 100) + ', 60)'" @click="step = i" style="cursor:pointer">
                            <circle r="16" :fill="step === i ? '#55E6FF' : '#0D1422'" :stroke="step === i ? '#55E6FF' : '#55E6FF'" stroke-opacity="0.6" stroke-width="1.5"/>
                            <text text-anchor="middle" dy="4" font-size="11" font-family="monospace" :fill="step === i ? '#04121a' : '#55E6FF'" x-text="i + 1"></text>
                            <text text-anchor="middle" dy="34" font-size="9" font-family="monospace" fill="#A5B0C5" x-text="s.t"></text>
                        </g>
                    </template>
                </svg>
                <div class="flex-1 min-w-0" aria-live="polite">
                    <p class="font-mono text-[11px] tracking-[0.24em] text-accent-soft" x-text="'STAGE 0' + (step + 1) + ' — ' + steps[step].t.toUpperCase()"></p>
                    <p class="mt-2 text-sm sm:text-base leading-relaxed text-surface-600 dark:text-term-800" x-text="steps[step].d"></p>
                </div>
            </div>
            <noscript><p class="mt-4 text-sm text-term-700">Seven stages: trigger, input, AI processing, tools, human approval, action, log.</p></noscript>
        </div>
    </div>
</section>

{{-- ═══════════ 04 / PROCESS ═══════════ --}}
@if(!empty($service->process_steps) && count($service->process_steps) > 0)
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="process-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="04" label="AI://DELIVERY"
            title="How we deliver {{ strtolower($service->name) }}."
            desc="A fixed delivery spine — scoped, tracked, and signed off at every stage." />
        <span id="process-heading" class="sr-only">Delivery process</span>
        <ol class="mt-10 grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($service->process_steps as $ps)
            <li class="term-panel-2 p-5 sm:p-6 reveal">
                <div class="font-mono text-[11px] tracking-[0.24em] text-accent-soft">{{ str_pad((string)($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                <h3 class="mt-2 font-display text-base font-bold tracking-tight text-navy-900 dark:text-white">{{ is_array($ps) ? ($ps['t'] ?? $ps['title'] ?? '') : $ps }}</h3>
                @if(is_array($ps))
                <p class="mt-2 text-[13px] leading-relaxed text-surface-600 dark:text-term-800">{{ $ps['d'] ?? $ps['description'] ?? '' }}</p>
                @endif
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- ═══════════ 05 / DELIVERABLES ═══════════ --}}
@if(!empty($service->deliverables) && count($service->deliverables) > 0)
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="deliverables-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="05" label="AI://DELIVERABLES"
            title="What you receive."
            desc="Tangible, checkable outputs — every item tracked in your service record." />
        <span id="deliverables-heading" class="sr-only">Deliverables</span>
        <ul class="mt-10 grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($service->deliverables as $item)
            <li class="term-panel p-5 sm:p-6 flex items-start gap-3 reveal">
                <span class="w-1.5 h-1.5 bg-accent flex-shrink-0 mt-1.5" aria-hidden="true"></span>
                <span class="text-sm leading-relaxed text-navy-900 dark:text-term-950">{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- ═══════════ 06 / FAQ ═══════════ --}}
@if(!empty($service->faq) && count($service->faq) > 0)
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="service-faq-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="06" label="AI://QUESTIONS"
            title="Service-specific questions."
            desc="Answered plainly — including limits and prerequisites." />
        <span id="service-faq-heading" class="sr-only">Frequently asked questions</span>
        <div class="mt-10 grid gap-4 sm:gap-5 lg:grid-cols-2 max-w-5xl">
            @foreach($service->faq as $entry)
            @php
                $parts = preg_split('/\s+[—–-]\s+/', (string) $entry, 2);
                $q = $parts[0] ?? $entry;
                $a = $parts[1] ?? '';
            @endphp
            <details class="term-panel p-5 sm:p-6 group reveal">
                <summary class="cursor-pointer font-display text-base font-bold tracking-tight text-navy-900 dark:text-white list-none flex items-center justify-between gap-4">
                    {{ $q }}
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                @if($a)<p class="mt-3 text-sm leading-relaxed text-surface-600 dark:text-term-800">{{ $a }}</p>@endif
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ 07 / RELATED ═══════════ --}}
@if($related->isNotEmpty())
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="related-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <x-section-head num="07" label="AI://RELATED"
            title="Commonly scoped together."
            desc="Adjacent AI services frequently combined in one engagement." />
        <span id="related-heading" class="sr-only">Related services</span>
        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($related as $rel)
            <a href="{{ route('ai.show', $rel->slug) }}" class="term-panel p-6 group block reveal">
                <div class="term-tag mb-3 inline-block">{{ $rel->category->name }}</div>
                <h3 class="font-display text-lg font-bold tracking-tight text-navy-900 dark:text-white group-hover:text-accent-soft transition-colors">{{ $rel->name }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-surface-600 dark:text-term-800 line-clamp-2">{{ $rel->short_description }}</p>
                <span class="term-link mt-4 text-sm">Explore
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ 08 / CTA ═══════════ --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="service-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="term-tag term-tag-accent">AI://DEPLOY</span>
        <h2 id="service-cta-heading" class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-navy-900 dark:text-white text-balance">Ready to scope {{ strtolower($service->name) }}?</h2>
        <p class="mt-4 text-base leading-relaxed text-surface-600 dark:text-term-800">Tell us your objective and environment — we respond with a written proposal, not a sales call ambush.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}" class="term-btn term-btn-lg btn-premium">Request a Custom Proposal</a>
            <a href="{{ route('ai.index') }}" class="term-btn term-btn-lg term-btn-ghost btn-ghost-premium">All AI Services →</a>
        </div>
    </div>
</section>

@endsection
