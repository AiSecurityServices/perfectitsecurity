@extends('layouts.public')

@section('title', 'IT Services Catalog — AISecurity')
@section('description', 'Enterprise IT services catalog: cybersecurity defense, cloud infrastructure, managed IT support, web development, and digital automation.')

@section('content')
@php
    // Category art map for reference service-card visual headers.
    $artFor = function ($slug) {
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
    $iconFor = function ($slug) {
        $slug = strtolower($slug ?? '');
        if (str_contains($slug, 'cybersecurity')) {
            return '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3zM9.2 12l1.9 1.9L15 10"/>';
        }
        if (str_contains($slug, 'cloud')) {
            return '<path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/>';
        }
        if (str_contains($slug, 'managed-it-support')) {
            return '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>';
        }
        if (str_starts_with($slug, 'ai')) {
            return '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z"/>';
        }
        return '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13h4l2 3h6l2-3h4M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>';
    };
@endphp

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="services-hero-heading" style="background:#020916;">
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-16 sm:pt-20 lg:pt-24 pb-10 lg:pb-14 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <nav class="flex flex-wrap items-center gap-2 text-[12px]" style="color:#7FB7E8;" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span aria-hidden="true">/</span>
                <span class="text-white">Services</span>
            </nav>
            <p class="ais-eyebrow mt-4">Our Services</p>
            <h1 id="services-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-[2.75rem] leading-tight">Engineered IT services for every layer of your business</h1>
            <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-xl">From managed cybersecurity to scalable cloud and custom business portals — explore our catalog of high-impact IT services.</p>
            <div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3">
                <a href="{{ route('get-quote') }}" class="ais-btn-primary">Get a Free Consultation
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="{{ route('contact') }}" class="ais-btn-ghost">Talk to an Expert</a>
            </div>
        </div>
        <div class="relative">
            <x-ais-globe />
            <span class="hero-float hero-float--tl"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/></svg>Cloud</strong></span>
            <span class="hero-float hero-float--ml"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z"/></svg>IT Support</strong></span>
            <span class="hero-float hero-float--br"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z"/></svg>24/7 Support</strong></span>
            <span class="hero-float hero-float--bc"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z"/></svg>Cybersecurity</strong></span>
        </div>
    </div>
</section>

{{-- Regional pricing selector (hooks preserved: countrySelector, country-btn, data-country-price) --}}
<section class="sticky top-14 z-30 border-y border-white/5" style="background:rgba(2,9,22,.95); backdrop-filter:blur(12px);" aria-label="Regional pricing">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-3.5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-[11px] font-semibold uppercase" style="letter-spacing:.18em; color:#7FB7E8;">Regional pricing:</span>
            <div class="flex flex-wrap gap-1.5 country-selector" id="countrySelector">
                @foreach($countries as $country)
                <button
                    type="button"
                    data-country="{{ $country->code }}"
                    class="country-btn"
                    style="padding:.35rem .8rem; border-radius:9999px; font-size:11.5px; font-weight:600; color:#DCEBFF; background:rgba(13,32,58,.7); border:1px solid rgba(0,207,255,.35); backdrop-filter:blur(10px); transition:border-color .25s, background-color .25s;"
                >
                    {{ $country->currency_symbol }} {{ $country->code }} — {{ $country->name }}
                </button>
                @endforeach
            </div>
        </div>
        <div class="text-[11px] hidden sm:block" style="color:#7FB7E8;">
            Showing verified multi-currency pricing
        </div>
    </div>
</section>

{{-- Featured services --}}
@if($featured->count() > 0)
<section class="relative w-full border-b border-white/5" aria-labelledby="featured-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <p class="ais-eyebrow">Featured Services</p>
        <h2 id="featured-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Most in-demand solutions</h2>
        <p class="ais-sub mt-3 text-[13px] max-w-3xl">High-impact deployments ordered most often by our clients — verified multi-currency pricing on every service.</p>

        <div class="mt-7 grid sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @foreach($featured->take(6) as $service)
            <article class="ais-card group overflow-hidden reveal flex flex-col">
                <div class="h-28 relative flex items-center justify-center" style="background:{{ $artFor($service->category->slug ?? '') }};" aria-hidden="true">
                    <svg class="w-11 h-11" fill="none" viewBox="0 0 24 24" stroke="#BFE9FF" stroke-width="1.4">{!! $iconFor($service->category->slug ?? '') !!}</svg>
                    <div class="absolute inset-0" style="background:linear-gradient(180deg, transparent 30%, rgba(2,9,22,.85));"></div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-[13.5px] font-bold text-white leading-snug">
                        <a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a>
                    </h3>
                    <p class="mt-1.5 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $service->short_description }}</p>
                    <div class="pricing-display mt-2 min-w-0" aria-label="Pricing for {{ $service->name }}">
                        @forelse($service->countryPrices as $cp)
                            @if($cp->country)
                            <span data-country-price="{{ $cp->country->code }}" style="display:none">
                                <x-service-price :service="$service" :row="$cp" size="card" />
                            </span>
                            @endif
                        @empty
                            @if($service->allows_custom_quote)
                                <span class="text-[11px] font-bold uppercase" style="letter-spacing:.08em; color:#00CFFF;">Custom Quote</span>
                            @endif
                        @endforelse
                    </div>
                    <div class="mt-3 pt-3 flex items-center justify-between gap-2" style="border-top:1px solid rgba(255,255,255,.06);">
                        <a href="{{ route('services.show', $service->slug) }}" class="ais-link" aria-label="Details for {{ $service->name }}">Learn More
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                        <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}"
                           class="text-[12px] font-semibold hover:text-white transition-colors" style="color:#00CFFF;" aria-label="Order {{ $service->name }}">
                            Order
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- All categories --}}
@foreach($categories as $category)
@if($category->services->count() > 0)
<section class="relative w-full border-b border-white/5" aria-labelledby="cat-{{ $category->id }}" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="max-w-3xl">
            <p class="ais-eyebrow">{{ $category->name }}</p>
            <h2 id="cat-{{ $category->id }}" class="ais-h2 mt-3 text-2xl sm:text-3xl">{{ $category->description ?? $category->name }}</h2>
            <p class="ais-sub mt-3 text-[13px]">{{ $category->services->count() }} service{{ $category->services->count() === 1 ? '' : 's' }} in {{ $category->name }}</p>
        </div>

        <div class="mt-7 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
            @foreach($category->services as $service)
            <article class="ais-card group overflow-hidden reveal flex flex-col">
                <div class="h-28 relative flex items-center justify-center" style="background:{{ $artFor($category->slug ?? '') }};" aria-hidden="true">
                    <svg class="w-11 h-11" fill="none" viewBox="0 0 24 24" stroke="#BFE9FF" stroke-width="1.4">{!! $iconFor($category->slug ?? '') !!}</svg>
                    <div class="absolute inset-0" style="background:linear-gradient(180deg, transparent 30%, rgba(2,9,22,.85));"></div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-[13.5px] font-bold text-white leading-snug"><a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a></h3>
                    <p class="mt-1.5 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $service->short_description }}</p>
                    <div class="pricing-display mt-2 min-w-0" aria-label="Pricing for {{ $service->name }}">
                        @forelse($service->countryPrices as $cp)
                            @if($cp->country)
                            <span data-country-price="{{ $cp->country->code }}" style="display:none">
                                <x-service-price :service="$service" :row="$cp" size="card" />
                            </span>
                            @endif
                        @empty
                            @if($service->allows_custom_quote)
                                <span class="text-[11px] font-bold" style="color:#00CFFF;">Custom Quote</span>
                            @else
                                <span class="text-[12px]" style="color:#B6C8DD;">Contact for pricing</span>
                            @endif
                        @endforelse
                    </div>
                    <div class="mt-3 pt-3 flex items-center justify-between gap-2" style="border-top:1px solid rgba(255,255,255,.06);">
                        <a href="{{ route('services.show', $service->slug) }}" class="ais-link" aria-label="Details for {{ $service->name }}">Learn More
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                        <a href="{{ auth()->check() ? route('portal.service-request.create', ['service_id' => $service->id]) : route('get-quote', ['service_id' => $service->id]) }}"
                           class="text-[12px] font-semibold hover:text-white transition-colors" style="color:#00CFFF;" aria-label="Order {{ $service->name }}">
                            Order
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endforeach

{{-- Custom work CTA --}}
<section class="relative w-full" aria-labelledby="custom-cta-heading" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="ais-card p-8 sm:p-10 lg:p-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <p class="ais-eyebrow">Custom Work</p>
                <h2 id="custom-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Require a custom architecture or support package?</h2>
                <p class="ais-sub mt-3 text-[13px] leading-relaxed">
                    Can't find an exact match for your infrastructure requirements? Our architects design tailored engineering and support agreements with guaranteed SLAs.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row flex-shrink-0 gap-3">
                <a href="{{ auth()->check() ? route('portal.service-request.create') : route('login') }}"
                   class="ais-btn-primary">
                    Order Custom Work
                </a>
                <a href="{{ route('contact') }}" class="ais-btn-ghost">
                    Free Consultation
                </a>
            </div>
        </div>
    </div>
</section>

<script>
// Country selector with local storage persistence
document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem('selected_country') || 'UK';
    setActiveCountry(saved);

    document.querySelectorAll('.country-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const country = this.dataset.country;
            localStorage.setItem('selected_country', country);
            setActiveCountry(country);
        });
    });

    function setActiveCountry(code) {
        document.querySelectorAll('.country-btn').forEach(btn => {
            if (btn.dataset.country === code) {
                btn.classList.remove('text-slate-300', 'border-white/10');
                btn.classList.add('bg-accent', 'text-black', 'border-accent', 'font-bold');
            } else {
                btn.classList.remove('bg-accent', 'text-black', 'border-accent', 'font-bold');
                btn.classList.add('text-slate-300', 'border-white/10');
            }
        });

        document.querySelectorAll('[data-country-price]').forEach(el => {
            el.style.display = (el.dataset.countryPrice === code) ? '' : 'none';
        });

        document.querySelectorAll('.pricing-display').forEach(function(display) {
            var priceSpans = display.querySelectorAll('[data-country-price]');
            if (priceSpans.length > 0) {
                var hasVisible = Array.from(priceSpans).some(function(el) { return el.style.display !== 'none'; });
                if (!hasVisible) {
                    priceSpans[0].style.display = '';
                }
            }
        });
    }
});
</script>


<x-ais-cta />

@endsection
