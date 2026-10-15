@extends('layouts.public')

@section('title', $service->name . ' — AISecurity')
@section('description', $service->seo_description ?? $service->short_description)

@section('content')
@php
    // Category art for the related-services visual headers (reference pattern).
    $relArtFor = function ($slug) {
        $slug = strtolower($slug ?? '');
        if (str_contains($slug, 'cybersecurity')) {
            return 'radial-gradient(circle at 50% 30%, rgba(0,207,255,.5), transparent 55%),linear-gradient(180deg, rgba(9,30,66,.9), rgba(4,19,38,.95))';
        }
        if (str_contains($slug, 'cloud')) {
            return 'radial-gradient(circle at 50% 35%, rgba(0,207,255,.55), transparent 60%),linear-gradient(180deg, rgba(22,139,255,.35), rgba(4,19,38,.95))';
        }
        if (str_contains($slug, 'managed-it-support')) {
            return 'linear-gradient(160deg, rgba(22,139,255,.5), rgba(4,19,38,.9)),repeating-linear-gradient(90deg, rgba(0,207,255,.15) 0 2px, transparent 2px 14px)';
        }
        if (str_starts_with($slug, 'ai')) {
            return 'radial-gradient(circle at 70% 30%, rgba(121,88,255,.5), transparent 55%),radial-gradient(circle at 30% 60%, rgba(0,207,255,.4), transparent 55%),linear-gradient(180deg, rgba(9,30,66,.9), rgba(4,19,38,.95))';
        }
        return 'linear-gradient(160deg, rgba(22,139,255,.35), rgba(4,19,38,.95))';
    };
@endphp

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="service-hero-heading" style="background:#020916;">
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-16 sm:pt-20 lg:pt-24 pb-10 lg:pb-14">
        <nav class="flex flex-wrap items-center gap-2 text-[12px]" style="color:#7FB7E8;" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('services.index') }}" class="hover:text-white transition-colors">Services</a>
            <span aria-hidden="true">/</span>
            <span class="text-white">{{ $service->name }}</span>
        </nav>
        <div class="mt-6 grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
            <div>
                <p class="ais-eyebrow">{{ $service->category->name ?? 'Our Services' }}</p>
                <h1 id="service-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-[2.75rem] leading-tight">{{ $service->name }}</h1>
                <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed">{{ $service->short_description }}</p>
                <div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3">
                    <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('login') }}" class="ais-btn-primary">
                        Order Now
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @if($service->allows_custom_quote)
                        <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('login') }}" class="ais-btn-ghost">
                            Get Custom Quote
                        </a>
                    @endif
                    <a href="{{ route('contact') }}" class="ais-btn-ghost">
                        Contact Us
                    </a>
                </div>
            </div>
            <div>
                <div class="ais-card p-8 sm:p-10 text-center lg:text-right">
                    <div class="text-[11px] font-semibold uppercase" style="letter-spacing:.18em; color:#7FB7E8;">Starting from</div>
                    @foreach($service->countryPrices as $cp)
                        @if($cp->country)
                        <div data-country-price="{{ $cp->country->code }}" style="display:none">
                            @if($cp->pricing_type === 'custom_quote')
                                <div class="text-4xl font-bold text-white mb-1" style="font-family:Sora,Inter,system-ui,sans-serif;">Custom Quote</div>
                                <div class="text-sm" style="color:#B6C8DD;">Tailored to your requirements</div>
                            @else
                                <x-service-price :service="$service" :row="$cp" size="hero" />
                                <div class="text-sm uppercase mt-1" style="letter-spacing:.08em; color:#B6C8DD;">{{ ucfirst($cp->pricing_type) }}</div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    @if($service->countryPrices->isEmpty())
                        <div class="text-4xl font-bold text-white mb-1" style="font-family:Sora,Inter,system-ui,sans-serif;">Custom Quote</div>
                        <div class="text-sm" style="color:#B6C8DD;">Tailored to your requirements</div>
                    @endif
                    @if($service->estimated_completion)
                        <div class="mt-4 pt-4 text-[12px]" style="border-top:1px solid rgba(255,255,255,.08); color:#7FB7E8;">
                            Estimated completion: {{ $service->estimated_completion }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-8"><x-ais-globe :chips="[
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity', 'tl'],
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud', 'tr'],
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z\'/>', '24/7 Support', 'br'],
        ]" /></div>
    </div>
</section>

{{-- All country prices --}}
@if($service->countryPrices->count() > 0)
<section class="border-y border-white/5" aria-label="Pricing by market" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-8">
        <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto">
            <span class="text-[11px] font-semibold uppercase whitespace-nowrap" style="letter-spacing:.18em; color:#7FB7E8;">Available in every market</span>
            @foreach($service->countryPrices as $cp)
            <div class="ais-card flex items-center gap-3 whitespace-nowrap px-4 py-2.5">
                <span class="text-sm font-bold" style="color:#00CFFF;">{{ $cp->country->currency_symbol }}</span>
                <div>
                    <div class="font-bold text-sm text-white">
                        @if($cp->pricing_type === 'custom_quote')
                            Custom Quote
                        @elseif($cp->discount_price && $cp->discount_valid_until && \Carbon\Carbon::parse($cp->discount_valid_until)->isFuture())
                            <span class="line-through opacity-50 text-xs mr-1" style="color:#B6C8DD;">{{ $cp->country->currency_symbol }}{{ number_format($cp->price, 0) }}</span>
                            <span style="color:#00CFFF;">{{ $cp->country->currency_symbol }}{{ number_format($cp->discount_price, 0) }}</span>
                            <span class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(0,207,255,.14); color:#00CFFF;">-{{ (int) round((1 - $cp->discount_price / max($cp->price, 0.01)) * 100) }}%</span>
                        @elseif(($__promo = app(\App\Services\PromotionService::class)->priceFor($service, $cp)) && $__promo['applies'])
                            <span class="line-through opacity-50 text-xs mr-1" style="color:#B6C8DD;">{{ $cp->country->currency_symbol }}{{ number_format($__promo['original'], 0) }}</span>
                            <span style="color:#00CFFF;">{{ $cp->country->currency_symbol }}{{ number_format($__promo['final'], 0) }}</span>
                            <span class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(0,207,255,.14); color:#00CFFF;">-{{ rtrim(rtrim(number_format($__promo['percent'], 1), '0'), '.') }}%</span>
                        @elseif($cp->pricing_type === 'starting_from')
                            From {{ $cp->country->currency_symbol }}{{ number_format($cp->price, 0) }}
                        @elseif($cp->pricing_type === 'hourly')
                            {{ $cp->country->currency_symbol }}{{ number_format($cp->price, 2) }}/hr
                        @elseif($cp->pricing_type === 'monthly' || $cp->pricing_type === 'recurring')
                            {{ $cp->country->currency_symbol }}{{ number_format($cp->price, 0) }}/mo
                        @else
                            {{ $cp->country->currency_symbol }}{{ number_format($cp->price, 0) }}
                        @endif
                    </div>
                    <div class="text-[10px]" style="letter-spacing:.06em; color:#7FB7E8;">{{ $cp->country->name }} ({{ $cp->country->currency_code }})</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Description --}}
@if($service->description)
<section class="relative w-full" aria-label="Service description" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="max-w-3xl">
            <p class="ais-eyebrow">Overview</p>
            <h2 class="ais-h2 mt-3 text-2xl sm:text-3xl">Service overview</h2>
            <div class="ais-card p-6 sm:p-8 mt-7">
                <div class="prose dark:prose-invert prose-lg max-w-none" style="color:#B6C8DD;">
                    {!! $service->description !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Deliverables & Features --}}
@if(($service->deliverables && count($service->deliverables) > 0) || ($service->features && count($service->features) > 0))
<section class="relative w-full border-t border-white/5" aria-label="Deliverables and features" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <p class="ais-eyebrow">Scope</p>
        <h2 class="ais-h2 mt-3 text-2xl sm:text-3xl">Scope of delivery</h2>
        <p class="ais-sub mt-3 text-[13px] max-w-3xl">Every line item is tracked in your service record.</p>
        <div class="mt-7 grid lg:grid-cols-2 gap-3.5">
            @if($service->deliverables && count($service->deliverables) > 0)
            <div class="ais-card p-6 sm:p-8">
                <h3 class="text-[11px] font-semibold uppercase" style="letter-spacing:.18em; color:#00CFFF;">Deliverables</h3>
                <ul class="mt-5 space-y-3">
                    @foreach($service->deliverables as $item)
                    <li class="flex items-start gap-3">
                        <span class="w-1.5 h-1.5 flex-shrink-0 mt-1.5 rounded-full" style="background:#00CFFF;" aria-hidden="true"></span>
                        <span class="text-sm" style="color:#B6C8DD;">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($service->features && count($service->features) > 0)
            <div class="ais-card p-6 sm:p-8">
                <h3 class="text-[11px] font-semibold uppercase" style="letter-spacing:.18em; color:#00CFFF;">Key features</h3>
                <ul class="mt-5 space-y-3">
                    @foreach($service->features as $item)
                    <li class="flex items-start gap-3">
                        <span class="w-1.5 h-1.5 flex-shrink-0 mt-1.5 rounded-full" style="background:#4DA3FF;" aria-hidden="true"></span>
                        <span class="text-sm" style="color:#B6C8DD;">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Process --}}
<section class="relative w-full border-t border-white/5" aria-label="Delivery process" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <p class="ais-eyebrow">Process</p>
        <h2 class="ais-h2 mt-3 text-2xl sm:text-3xl">How we deliver {{ strtolower($service->name) }}</h2>
        <p class="ais-sub mt-3 text-[13px] max-w-3xl">A fixed four-stage workflow — scoped, tracked, and signed off.</p>
        <ol class="mt-7 grid sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            @foreach(['Consult' => 'We discuss your requirements and provide a tailored proposal.', 'Plan' => 'Our team creates a detailed project plan with clear milestones.', 'Deliver' => 'Execution follows strict quality standards with regular updates.', 'Support' => 'Ongoing support and optimization after delivery.'] as $title => $desc)
            <li class="ais-card p-5 sm:p-6">
                <div class="text-[11px] font-bold" style="letter-spacing:.18em; color:#00CFFF;">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                <h3 class="mt-2 text-[15px] font-bold text-white">{{ $title }}</h3>
                <p class="mt-2 text-[13px] leading-relaxed" style="color:#B6C8DD;">{{ $desc }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Related Services --}}
@if($related->isNotEmpty())
<section class="relative w-full border-t border-white/5" aria-label="Related services" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <p class="ais-eyebrow">Related Services</p>
        <h2 class="ais-h2 mt-3 text-2xl sm:text-3xl">Explore our capabilities</h2>
        <p class="ais-sub mt-3 text-[13px] max-w-3xl">Services frequently scoped together with {{ strtolower($service->name) }}.</p>
        <div class="mt-7 grid sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @foreach($related as $rel)
            <article class="ais-card group overflow-hidden reveal flex flex-col">
                <div class="h-28 relative flex items-center justify-center" style="background:{{ $relArtFor($rel->category->slug ?? '') }};" aria-hidden="true">
                    <svg class="w-11 h-11" fill="none" viewBox="0 0 24 24" stroke="#BFE9FF" stroke-width="1.4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h4l2 3h6l2-3h4M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                    <div class="absolute inset-0" style="background:linear-gradient(180deg, transparent 30%, rgba(2,9,22,.85));"></div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-[13.5px] font-bold text-white leading-snug">{{ $rel->name }}</h3>
                    <p class="mt-1.5 text-[12px] leading-relaxed flex-1" style="color:#B6C8DD;">{{ $rel->short_description }}</p>
                    @php
                        $relPrice = $rel->countryPrices->firstWhere('country.code', 'UK') ?? $rel->countryPrices->firstWhere('country.code', 'US') ?? $rel->countryPrices->first();
                    @endphp
                    @if($relPrice && $relPrice->pricing_type !== 'custom_quote')
                        <div class="mt-2 text-sm">
                            @if($relPrice->discount_price && $relPrice->discount_valid_until && \Carbon\Carbon::parse($relPrice->discount_valid_until)->isFuture())
                                <span class="line-through text-xs" style="color:#7FB7E8;">{{ $relPrice->country->currency_symbol }}{{ number_format($relPrice->price, 0) }}</span>
                                <span class="font-bold ml-1" style="color:#00CFFF;">{{ $relPrice->country->currency_symbol }}{{ number_format($relPrice->discount_price, 0) }}</span>
                                <span class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(0,207,255,.14); color:#00CFFF;">-{{ (int) round((1 - $relPrice->discount_price / max($relPrice->price, 0.01)) * 100) }}%</span>
                            @else
                                <span class="font-bold text-white">{{ $relPrice->country->currency_symbol }}{{ number_format($relPrice->price, 0) }}</span>
                            @endif
                        </div>
                    @endif
                    <a href="{{ route('services.show', $rel->slug) }}" class="ais-link mt-3">Learn More
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="relative w-full border-t border-white/5" aria-labelledby="service-cta-heading" style="background:#020916;">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-12 lg:py-16 text-center">
        <p class="ais-eyebrow">Get Started</p>
        <h2 id="service-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Ready to get started?</h2>
        <p class="ais-sub mt-3 text-[13px]">Let's discuss how {{ strtolower($service->name) }} can support your organization.</p>
        <div class="mt-7 flex flex-wrap justify-center gap-3">
            <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}" class="ais-btn-primary">
                    Order Now
                </a>
                @if($service->allows_custom_quote)
                    <a href="{{ route('get-quote', ['service_id' => $service->id]) }}" class="ais-btn-ghost">
                        Get Custom Quote
                    </a>
                @endif
                <a href="{{ route('contact') }}" class="ais-btn-ghost">
                    Contact Us
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var saved = localStorage.getItem('selected_country') || 'UK';
    showHeroPrice(saved);

    function showHeroPrice(code) {
        var priceEls = document.querySelectorAll('[data-country-price]');
        priceEls.forEach(function(el) {
            el.style.display = (el.dataset.countryPrice === code) ? '' : 'none';
        });
        // Fallback: if no price for selected country, show first available
        if (priceEls.length > 0) {
            var hasVisible = Array.from(priceEls).some(function(el) { return el.style.display !== 'none'; });
            if (!hasVisible) {
                priceEls[0].style.display = '';
            }
        }
    }
});
</script>

<x-ais-cta title="Ready to scope this service?" text="Tell us your objective and environment — we respond with a written proposal." />

@endsection
