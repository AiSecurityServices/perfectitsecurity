@extends('layouts.public')

@section('title', ($service->seo_title ?: $service->name . ' | AISecurity'))
@section('description', ($service->seo_description ?: $service->short_description))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $service->name,
    'description' => $service->short_description,
    'url' => route('ai.show', $service->slug),
    'provider' => ['@type' => 'Organization', 'name' => 'AISecurity', 'url' => url('/')],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="ais-page">

{{-- ═══════════ HERO ═══════════ --}}
<section class="relative w-full overflow-hidden" aria-labelledby="service-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 lg:pt-20 pb-10 lg:pb-14 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <nav aria-label="Breadcrumb" class="reveal">
                <ol class="ais-crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('ai.index') }}">AI Agent Services</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">{{ $service->name }}</li>
                </ol>
            </nav>
            <p class="ais-eyebrow reveal mt-5">{{ $service->category->name }}</p>
            <h1 id="service-heading" class="mt-4 font-extrabold tracking-tight leading-[1.05] text-4xl sm:text-5xl text-balance reveal" style="font-family:Sora,Inter,system-ui,sans-serif;">
                <span class="text-white">{{ $service->name }}</span>
            </h1>
            <p class="mt-5 text-[14px] sm:text-[15px] leading-relaxed max-w-xl reveal delay-100" style="color:#B6C8DD;">{{ $service->short_description }}</p>
            <div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3 reveal delay-200">
                <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}" class="ais-btn-primary">
                    Request a Custom Proposal
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="ais-btn-ghost">Discuss This Service</a>
            </div>
        </div>
        <div>
            <x-ais-globe :chips="[
                ['<circle cx=\'12\' cy=\'8\' r=\'3.5\'/><path stroke-linecap=\'round\' d=\'M5 20c1.5-3.5 4-5 7-5s5.5 1.5 7 5\'/>', 'AI Agents', 'tl'],
                ['<circle cx=\'12\' cy=\'12\' r=\'3\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 2v3m0 14v3M2 12h3m14 0h3M5 5l2 2m10 10l2 2M19 5l-2 2M7 17l-2 2\'/>', 'Automation', 'tr'],
                ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud', 'mr'],
                ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity', 'bc'],
            ]" />
            <div class="ais-card p-6 mt-4" aria-label="Service facts">
                <dl class="space-y-4 text-[12px] tracking-wider">
                    <div class="flex justify-between gap-4"><dt style="color:#7d93ad;">PRICING</dt><dd class="text-right font-semibold" style="color:#00CFFF;">Custom Proposal</dd></div>
                    @if($service->estimated_completion)
                    <div class="flex justify-between gap-4"><dt style="color:#7d93ad;">DELIVERY</dt><dd class="text-white text-right">{{ $service->estimated_completion }}</dd></div>
                    @endif
                    <div class="flex justify-between gap-4"><dt style="color:#7d93ad;">DIVISION</dt><dd class="text-white text-right">{{ $service->category->name }}</dd></div>
                    <div class="flex justify-between gap-4"><dt style="color:#7d93ad;">SUPPORT</dt><dd class="text-white text-right">Ticketed &amp; tracked</dd></div>
                </dl>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ PROBLEM & SOLUTION ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="problem-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-7">
            <x-ais-section-head id="problem-heading" eyebrow="The Solution"
                title="The problem this solves."
                sub="Concrete operational pain — and exactly how this service addresses it." />
            <p class="mt-6 text-[14px] sm:text-[15px] leading-relaxed reveal" style="color:#B6C8DD;">{{ $service->description }}</p>
        </div>
        <div class="lg:col-span-5 flex flex-col gap-3.5">
            <div class="ais-card p-6 reveal">
                <div class="text-[11px] font-bold tracking-[0.2em] uppercase mb-3" style="color:#00CFFF;">In scope</div>
                <p class="text-[13px] leading-relaxed" style="color:#B6C8DD;">{{ $service->scope }}</p>
            </div>
            @if($service->exclusions)
            <div class="ais-card p-6 reveal">
                <div class="text-[11px] font-bold tracking-[0.2em] uppercase mb-3" style="color:#7FB7E8;">Excluded — by design</div>
                <p class="text-[13px] leading-relaxed" style="color:#B6C8DD;">{{ $service->exclusions }}</p>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══════════ CAPABILITIES ═══════════ --}}
@if(!empty($service->features) && count($service->features) > 0)
<section class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="capabilities-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="capabilities-heading" eyebrow="Capabilities"
            title="What this service includes."
            sub="Each capability is configured for your environment — never a generic install-and-leave." />
        <div class="mt-8 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($service->features as $i => $feature)
            <div class="ais-card p-5 reveal">
                <div class="text-[11px] font-bold tracking-[0.22em]" style="color:#00CFFF;">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }}</div>
                <p class="mt-3 text-[13px] leading-relaxed text-white">{{ $feature }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ INTERACTIVE WORKFLOW VISUALIZATION ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="workflow-heading"
    x-data="{ step: 3, steps: [
        { t: 'Trigger', d: 'A defined event starts the workflow — a new enquiry, a schedule, or a human request. Nothing runs uninvited.' },
        { t: 'Input', d: 'Only the data the task needs is collected from approved sources — inboxes, documents, CRM, or your knowledge base.' },
        { t: 'AI Processing', d: 'The model reasons over the inputs within its instructions. Capabilities depend on the selected model and configuration.' },
        { t: 'Tools', d: 'The agent may call approved integrations via official APIs — search, tickets, calendars, documents. Each tool is permissioned.' },
        { t: 'Human Approval', d: 'Consequential or irreversible actions pause here for a person to review and authorize. Low-risk steps may proceed per policy.' },
        { t: 'Action', d: 'The approved action executes — a draft created, a record updated, a message queued for sending.' },
        { t: 'Log', d: 'Every step is written to an audit log: what ran, what was approved, and what changed. Failures alert, never vanish.' }
    ] }">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="workflow-heading" eyebrow="Agent Workflow"
            title="How an agent workflow actually runs."
            sub="Select any stage to inspect it. This is the documented pattern behind every automation we ship — trigger to log." />
        <div class="mt-8 ais-card p-6 sm:p-8 reveal">
            <div class="flex flex-wrap gap-2" role="group" aria-label="Workflow stages">
                <template x-for="(s, i) in steps" :key="i">
                    <button type="button" @click="step = i" :aria-pressed="step === i"
                        :class="step === i ? 'text-white border-[rgba(0,207,255,.65)] bg-[rgba(0,207,255,.14)]' : 'text-[#B6C8DD] border-white/10 hover:border-[rgba(0,207,255,.4)]'" class="cursor-pointer rounded-full border px-3 py-1.5 text-[11px] tracking-wider transition"
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
                    <p class="text-[11px] font-bold tracking-[0.2em]" style="color:#00CFFF;" x-text="'STAGE 0' + (step + 1) + ' — ' + steps[step].t.toUpperCase()"></p>
                    <p class="mt-2 text-sm sm:text-[15px] leading-relaxed" style="color:#B6C8DD;" x-text="steps[step].d"></p>
                </div>
            </div>
            <noscript><p class="mt-4 text-sm" style="color:#B6C8DD;">Seven stages: trigger, input, AI processing, tools, human approval, action, log.</p></noscript>
        </div>
    </div>
</section>

{{-- ═══════════ PROCESS ═══════════ --}}
@if(!empty($service->process_steps) && count($service->process_steps) > 0)
<section class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="process-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="process-heading" eyebrow="Delivery Process"
            title="How we deliver {{ strtolower($service->name) }}."
            sub="A fixed delivery spine — scoped, tracked, and signed off at every stage." />
        <ol class="mt-8 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($service->process_steps as $ps)
            <li class="ais-card p-5 reveal">
                <div class="text-[11px] font-bold tracking-[0.22em]" style="color:#00CFFF;">{{ str_pad((string)($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                <h3 class="mt-2 text-[13.5px] font-bold tracking-tight text-white">{{ is_array($ps) ? ($ps['t'] ?? $ps['title'] ?? '') : $ps }}</h3>
                @if(is_array($ps))
                <p class="mt-2 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">{{ $ps['d'] ?? $ps['description'] ?? '' }}</p>
                @endif
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- ═══════════ DELIVERABLES ═══════════ --}}
@if(!empty($service->deliverables) && count($service->deliverables) > 0)
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="deliverables-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="deliverables-heading" eyebrow="Deliverables"
            title="What you receive."
            sub="Tangible, checkable outputs — every item tracked in your service record." />
        <ul class="mt-8 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($service->deliverables as $item)
            <li class="ais-card p-5 flex items-start gap-3 reveal">
                <span class="mt-0.5 inline-flex w-[18px] h-[18px] rounded-full items-center justify-center flex-shrink-0" style="background:rgba(0,207,255,.15); border:1px solid rgba(0,207,255,.5);" aria-hidden="true"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                <span class="text-[13px] leading-relaxed text-white">{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- ═══════════ FAQ ═══════════ --}}
@if(!empty($service->faq) && count($service->faq) > 0)
<section class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="service-faq-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="service-faq-heading" eyebrow="Questions"
            title="Service-specific questions."
            sub="Answered plainly — including limits and prerequisites." />
        <div class="mt-8 grid gap-3.5 lg:grid-cols-2 max-w-5xl">
            @foreach($service->faq as $entry)
            @php
                $parts = preg_split('/\s+[—–-]\s+/', (string) $entry, 2);
                $q = $parts[0] ?? $entry;
                $a = $parts[1] ?? '';
            @endphp
            <details class="ais-card p-5 group reveal">
                <summary class="cursor-pointer text-[14px] font-bold tracking-tight text-white list-none flex items-center justify-between gap-4">
                    {{ $q }}
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="#00CFFF" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                @if($a)<p class="mt-3 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">{{ $a }}</p>@endif
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ RELATED ═══════════ --}}
@if($related->isNotEmpty())
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="related-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="related-heading" eyebrow="Related Services"
            title="Commonly scoped together."
            sub="Adjacent AI services frequently combined in one engagement." />
        @php
            $relPalette = [
                ['bg' => 'rgba(22,139,255,.18)', 'fg' => '#4DA3FF'],
                ['bg' => 'rgba(255,180,84,.16)', 'fg' => '#FFB454'],
                ['bg' => 'rgba(121,88,255,.18)', 'fg' => '#9275FF'],
                ['bg' => 'rgba(0,230,122,.14)', 'fg' => '#00E67A'],
                ['bg' => 'rgba(0,207,255,.14)', 'fg' => '#00CFFF'],
                ['bg' => 'rgba(255,92,92,.14)', 'fg' => '#FF8A8A'],
            ];
            $relIcon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/>';
        @endphp
        <div class="mt-8 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($related as $rel)
            @php $rc = $relPalette[$loop->index % count($relPalette)]; @endphp
            <a href="{{ route('ai.show', $rel->slug) }}" class="ais-card group block p-5 reveal" aria-label="{{ $rel->name }} — view service">
                <div class="flex items-start justify-between gap-2">
                    <span class="ais-icon" style="background:{{ $rc['bg'] }}; color:{{ $rc['fg'] }};" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $relIcon !!}</svg></span>
                    <svg class="w-4 h-4 mt-1 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="#7FB7E8" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </div>
                <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.14em]" style="color:#00CFFF;">{{ $rel->category->name }}</p>
                <h3 class="mt-2 text-[14px] font-bold text-white leading-snug">{{ $rel->name }}</h3>
                <p class="mt-1.5 text-[12px] leading-relaxed line-clamp-2" style="color:#B6C8DD;">{{ $rel->short_description }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════ CTA ═══════════ --}}
<section class="relative w-full border-t border-white/5" style="background:linear-gradient(180deg,#020916,#03101f);" aria-labelledby="service-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-12 lg:py-16 text-center">
        <p class="ais-eyebrow reveal">Delivery</p>
        <h2 id="service-cta-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl text-balance reveal">Ready to scope {{ strtolower($service->name) }}?</h2>
        <p class="ais-sub mt-4 text-[13.5px] max-w-xl mx-auto reveal delay-100">Tell us your objective and environment — we respond with a written proposal, not a sales call ambush.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3 reveal delay-200">
            <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}" class="ais-btn-primary">Request a Custom Proposal
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
            <a href="{{ route('ai.index') }}" class="ais-btn-ghost">All AI Services</a>
        </div>
    </div>
</section>

</div>
@endsection
