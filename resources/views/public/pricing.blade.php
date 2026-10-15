@extends('layouts.public')

@section('title', 'Enterprise Pricing & SLA Plans — AISecurity')
@section('description', 'Predictable, transparent enterprise IT service plans. Proactive cybersecurity, cloud administration, and dedicated 24/7 SOC infrastructure support.')

@section('content')
<div class="ais-page">

<x-ais-page-hero
    eyebrow="Pricing Plans"
    title="Predictable Pricing for <span class='ais-title-hl'>Uncompromising IT</span>"
    lede="Zero ambiguous hourly billing. Choose standardized enterprise support tiers with fixed SLA commitments, continuous proactive SOC monitoring, and scalable engineering capacity."
    :chips="[
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M5 13l4 4L19 7\'/>', 'Transparent'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'SLA-Backed'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z\'/>', '24/7 Support'],
    ]"
>
    <x-slot:breadcrumbs>
        <nav class="ais-crumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Pricing</span></nav>
    </x-slot:breadcrumbs>
    <x-slot:actions>
        <a href="{{ route('get-quote') }}" class="ais-btn-primary">Get a Custom Quote
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        <a href="{{ route('contact') }}" class="ais-btn-ghost">Talk to an Expert</a>
    </x-slot:actions>
</x-ais-page-hero>

{{-- ═════════ PROMOTION (logic intact) ═════════ --}}
@php try { $__promoSvc = app(\App\Services\PromotionService::class); $__promoOn = $__promoSvc->isActive(); $__promoCfg = $__promoSvc->campaign(); $__promoWin = $__promoSvc->window(); } catch (\Throwable $e) { $__promoOn = false; $__promoCfg = []; $__promoWin = []; } @endphp
@if($__promoOn)
<section class="relative w-full border-t border-white/5" aria-label="Current promotion" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-8">
        <div class="ais-glass p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 reveal" style="border-color:rgba(0,207,255,.45);">
            <div>
                <span class="ais-badge">Active Promotion</span>
                <h2 class="mt-2 ais-h2 text-xl sm:text-2xl">
                    {{ $__promoCfg['promo.name'] ?? 'Promotion' }} — {{ rtrim(rtrim(number_format((float) ($__promoCfg['promo.percent'] ?? 0), 1), '0'), '.') }}% off eligible support services
                </h2>
                <p class="mt-1 text-sm ais-sub">{{ $__promoCfg['promo.terms'] ?? '' }}@if(!empty($__promoWin['ends_at'])) Offer ends {{ $__promoWin['ends_at']->format('M d, Y') }} ({{ $__promoWin['timezone'] ?? 'UTC' }}).@endif</p>
            </div>
            <a href="{{ route('services.index') }}" class="ais-btn-primary flex-shrink-0 justify-center">Browse eligible services</a>
        </div>
    </div>
</section>
@endif

{{-- ═════════ PRICING PLANS ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-label="Pricing plans" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="grid lg:grid-cols-3 gap-4 sm:gap-5 items-stretch">
            @foreach($plans as $plan)
            <div class="ais-card p-7 sm:p-8 flex flex-col justify-between relative reveal">
                @if($plan->is_popular)
                <div class="absolute -top-3 left-6 z-20">
                    <span class="ais-badge" style="background:linear-gradient(94deg, #168BFF, #00CFFF); color:#fff; border-color:transparent;">Recommended Tier</span>
                </div>
                @endif

                <div>
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h3 class="ais-h2 text-xl">{{ $plan->name }}</h3>
                        <span class="ais-badge">
                            {{ strtoupper($plan->billing_cycle) }}
                        </span>
                    </div>

                    <p class="text-[13px] leading-relaxed ais-sub mb-6 min-h-[44px]">
                        {{ $plan->description }}
                    </p>

                    <div class="ais-glass px-5 py-5 mb-6 flex items-baseline gap-2">
                        <span class="text-4xl sm:text-5xl font-extrabold text-white">${{ number_format($plan->price) }}</span>
                        <span class="text-xs tracking-wider" style="color:#7FB7E8;">/ {{ $plan->billing_cycle }}</span>
                    </div>

                    <a href="{{ route('contact') }}?plan={{ Str::slug($plan->name) }}"
                       class="{{ $plan->is_popular ? 'ais-btn-primary' : 'ais-btn-ghost' }} w-full justify-center">
                        Deploy This Plan
                    </a>

                    @if($plan->features)
                    <div class="mt-6 pt-6 border-t border-white/10">
                        <p class="ais-eyebrow mb-4">What&rsquo;s included</p>
                        <ul class="space-y-2.5">
                            @foreach($plan->features as $feature)
                            <li class="ais-check">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ FAQ ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="pricing-faq-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <x-ais-section-head id="pricing-faq-heading" eyebrow="Pricing FAQ" title="Frequently asked questions." sub="Clarity on SLAs, scope, terms, and onboarding." />
        <div class="mt-7 grid md:grid-cols-2 gap-4 sm:gap-5">
            @foreach([
                ['q' => 'How are enterprise SLAs calculated and enforced?', 'a' => 'Our SLAs are backed by contractually guaranteed response times ranging from 15 minutes for critical P1 events to 2 hours for standard P3 requests, monitored live via automated telemetry.'],
                ['q' => 'Can we customize scope across multi-regional branches?', 'a' => 'Yes. We frequently architect hybrid agreements combining 24/7 centralized SOC coverage with regional localized field dispatch across North America, Europe, and Asia.'],
                ['q' => 'What is the contract duration and cancellation flexibility?', 'a' => 'We offer both monthly and discounted multi-year master service agreements. All agreements include standard 30-day satisfaction exit terms.'],
                ['q' => 'Are onboarding audits and infrastructure migrations included?', 'a' => 'Enterprise tiers include a complimentary comprehensive vulnerability audit and onboarding transition blueprint managed by a principal DevOps architect.'],
            ] as $faq)
            <div class="ais-card p-6 sm:p-7 reveal">
                <h3 class="text-[14.5px] font-bold text-white leading-snug">
                    {{ $faq['q'] }}
                </h3>
                <p class="mt-2.5 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">
                    {{ $faq['a'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ CTA ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="pricing-cta-heading" style="background:#020916;">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 text-center">
        <p class="ais-eyebrow">Custom Quote</p>
        <h2 id="pricing-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Need a specialized enterprise quote?</h2>
        <p class="ais-sub mt-3 text-[13px] max-w-2xl mx-auto">Our solutions engineering team can configure an exact hybrid package for your infrastructure scale.</p>
        <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('contact') }}" class="ais-btn-primary">
                Request Custom Architecture Proposal
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('get-quote') }}" class="ais-btn-ghost">Get a Free Quote</a>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
