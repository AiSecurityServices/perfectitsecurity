@extends('layouts.public')

@section('title', 'Global Offices — Our International Presence')
@section('description', 'AISecurity operates from strategic locations across the globe, providing 24/7 IT support, cybersecurity, and cloud services to enterprises worldwide.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="offices-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10 text-center">
        <nav class="ais-crumb mb-4 justify-center" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Offices</span>
        </nav>
        <p class="ais-eyebrow">Global presence</p>
        <h1 id="offices-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">
            Operating across 15+ countries
        </h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-3xl mx-auto">
            Our strategic global presence ensures we deliver 24/7/365 support with local expertise and international standards.
        </p>
    </div>
</section>

{{-- Stats --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Global statistics">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-10">
        <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @foreach([
                ['value' => '15+', 'label' => 'Countries'],
                ['value' => '24/7', 'label' => 'Global Coverage'],
                ['value' => '5', 'label' => 'Continents'],
                ['value' => '500+', 'label' => 'Team Members'],
            ] as $stat)
            <div class="ais-card px-5 py-5 text-center">
                <dd class="text-3xl lg:text-4xl font-bold text-white">{{ $stat['value'] }}</dd>
                <dt class="ais-eyebrow mt-2" style="font-size:10px;">{{ $stat['label'] }}</dt>
            </div>
            @endforeach
        </dl>
    </div>
</section>

{{-- Offices --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="hq-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="max-w-3xl reveal">
            <p class="ais-eyebrow">Regional offices</p>
            <h2 id="hq-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Regional headquarters.</h2>
            <p class="ais-sub mt-3 text-[13px]">Each office serves as a regional hub for operations, client engagement, and talent development.</p>
        </div>

        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach([
                ['city' => 'London', 'country' => 'United Kingdom', 'code' => 'LON', 'region' => 'EMEA HQ', 'address' => '1 Canada Square, Canary Wharf, London E14 5AB', 'phone' => '+44 20 7946 0958', 'services' => ['Cybersecurity', 'Penetration Testing', 'IT Consulting'], 'timezone' => 'GMT/BST'],
                ['city' => 'New York', 'country' => 'United States', 'code' => 'NYC', 'region' => 'Americas HQ', 'address' => '1 World Trade Center, New York, NY 10007', 'phone' => '+1 (212) 555-0100', 'services' => ['Cloud Architecture', 'Managed IT', 'Compliance'], 'timezone' => 'EST/CST'],
                ['city' => 'Dhaka', 'country' => 'Bangladesh', 'code' => 'DAC', 'region' => 'Asia-Pacific Hub', 'address' => 'Gulshan Avenue, Dhaka 1212', 'phone' => '+880 2 5501 2345', 'services' => ['Web Development', 'Software Engineering', 'Remote IT Support'], 'timezone' => 'BST (GMT+6)'],
                ['city' => 'Singapore', 'country' => 'Singapore', 'code' => 'SIN', 'region' => 'APAC Regional', 'address' => '1 Raffles Place, Singapore 048616', 'phone' => '+65 6100 0100', 'services' => ['Cloud Security', 'Network Infrastructure', 'AI Solutions'], 'timezone' => 'SGT (GMT+8)'],
                ['city' => 'Dubai', 'country' => 'United Arab Emirates', 'code' => 'DXB', 'region' => 'MENA Regional', 'address' => 'DIFC, Dubai, UAE', 'phone' => '+971 4 555 0100', 'services' => ['IT Consulting', 'Digital Transformation', 'Managed Services'], 'timezone' => 'GST (GMT+4)'],
                ['city' => 'Sydney', 'country' => 'Australia', 'code' => 'SYD', 'region' => 'Oceania', 'address' => '1 Macquarie Place, Sydney NSW 2000', 'phone' => '+61 2 5550 0100', 'services' => ['Cloud Migration', 'Disaster Recovery', 'IT Training'], 'timezone' => 'AEST (GMT+10)'],
            ] as $office)
            <div class="ais-card p-6 group">
                <div class="flex items-center gap-3 mb-4">
                    <span class="ais-icon !w-11 !h-11 font-bold text-xs" style="background:rgba(0,207,255,.08); border-color:rgba(0,207,255,.4); color:#00CFFF;" aria-hidden="true">{{ $office['code'] }}</span>
                    <div class="min-w-0">
                        <h3 class="text-lg font-bold tracking-tight text-white">{{ $office['city'] }}</h3>
                        <span class="ais-badge mt-1">{{ $office['region'] }}</span>
                    </div>
                </div>
                <p class="text-sm mb-1" style="color:#B6C8DD;">{{ $office['country'] }}</p>
                <p class="text-xs mb-1" style="color:#7FB7E8;">{{ $office['address'] }}</p>
                <p class="text-[11px] tracking-wider mb-3" style="color:#7FB7E8;">{{ $office['phone'] }} · {{ $office['timezone'] }}</p>
                <div class="flex flex-wrap gap-1.5 pt-3 border-t border-white/5">
                    @foreach($office['services'] as $svc)
                    <span class="ais-badge">{{ $svc }}</span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Service regions --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="served-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="max-w-3xl reveal">
            <p class="ais-eyebrow">Coverage</p>
            <h2 id="served-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Countries we serve.</h2>
            <p class="ais-sub mt-3 text-[13px]">Our services are available across 15+ countries with localised pricing and support.</p>
        </div>

        <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-w-5xl mx-auto">
            @php
            $servedCountries = App\Models\Country::where('is_active', true)->orderBy('name')->get();
            $flagMap = ['US' => 'USA', 'GB' => 'GBR', 'BD' => 'BGD', 'SG' => 'SGP', 'AE' => 'ARE', 'AU' => 'AUS', 'DE' => 'DEU', 'FR' => 'FRA', 'CA' => 'CAN', 'IN' => 'IND'];
            @endphp
            @forelse($servedCountries as $country)
            <div class="ais-card flex items-center gap-2.5 px-3 py-3">
                <span class="text-[10px] font-bold flex-shrink-0" style="color:#00CFFF;" aria-hidden="true">{{ $flagMap[strtoupper($country->code)] ?? 'GLB' }}</span>
                <div class="min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ $country->name }}</div>
                    <div class="text-[10px] tracking-wider" style="color:#7FB7E8;">{{ $country->currency_code }}</div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-sm py-8" style="color:#B6C8DD;">Country information available upon request.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="offices-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
        <p class="ais-eyebrow">Get in touch</p>
        <h2 id="offices-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Ready to work with us?</h2>
        <p class="ais-sub mt-4 text-[13px] sm:text-sm">Contact the nearest office for a free consultation and discover how we can support your business globally.</p>
        <a href="{{ route('contact') }}" class="ais-btn-primary mt-8">
            Contact Us Today
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
