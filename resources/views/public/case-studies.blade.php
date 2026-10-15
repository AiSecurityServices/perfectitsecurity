@extends('layouts.public')

@section('title', 'Case Studies — IT Solutions in Action')
@section('description', 'Educational demo case studies showing how structured IT, cybersecurity and software practices solve common business technology challenges.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="cs-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Case Studies</span>
        </nav>
        <p class="ais-eyebrow">Client success</p>
        <h1 id="cs-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">Client success stories</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            How structured IT, cybersecurity, and software practices solve common business technology challenges.
        </p>
    </div>
</section>

{{-- Case studies list --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Case studies">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <p class="text-sm mb-6 max-w-3xl" style="color:#B6C8DD;">Real-world patterns distilled into practical studies — each one highlights the lessons we apply in client work.</p>
        <form method="GET" action="{{ route('case-studies') }}" class="flex flex-col sm:flex-row flex-wrap gap-2.5 mb-10" role="search">
            <label for="cs-search" class="sr-only">Search case studies</label>
            <input type="text" id="cs-search" name="search" value="{{ request('search') }}" placeholder="Search case studies…"
                   class="ais-input sm:max-w-xs text-sm">
            <label for="cs-industry" class="sr-only">Filter by industry</label>
            <select name="industry" id="cs-industry" class="ais-input sm:max-w-xs text-sm">
                <option value="">All industries</option>
                @foreach($industries as $ind)
                    <option value="{{ $ind }}" @selected(request('industry') === $ind)>{{ $ind }}</option>
                @endforeach
            </select>
            <div class="flex gap-2.5">
                <button class="ais-btn-primary !py-2 !px-4 !text-xs">Filter</button>
                @if(request('search') || request('industry'))
                    <a href="{{ route('case-studies') }}" class="ais-btn-ghost !py-2 !px-4 !text-xs">Clear</a>
                @endif
            </div>
        </form>
        <div class="space-y-4 sm:space-y-5">
            @forelse($items as $item)
            <article class="ais-card p-4 sm:p-5">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-10 items-center">
                    <div>
                        <div class="flex flex-wrap items-center gap-1.5 mb-4">
                            @if($item->industry)<span class="ais-badge">{{ $item->industry }}</span>@endif
                        </div>
                        <h2 class="ais-h2 text-2xl sm:text-3xl">
                            <a href="{{ route('case-studies.show', $item->slug) }}">{{ $item->title }}</a>
                        </h2>
                        @if($item->summary)<p class="mt-3 text-sm leading-relaxed" style="color:#B6C8DD;">{{ $item->summary }}</p>@endif
                        <a href="{{ route('case-studies.show', $item->slug) }}" class="ais-link mt-5 text-sm">Read full study
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    <div class="ais-card p-5 sm:p-6">
                        <div class="text-[10px] tracking-[0.2em] uppercase mb-4" style="color:#7FB7E8;">Challenge → Solution → Lessons</div>
                        <div class="space-y-3 text-sm">
                            @if($item->challenge)<div class="ais-card px-4 py-3"><span class="text-[11px] font-bold" style="color:#00CFFF;">Challenge: </span><span class="text-[13px]" style="color:#B6C8DD;"> {{ \Illuminate\Support\Str::limit(strip_tags($item->challenge), 220) }}</span></div>@endif
                            @if($item->solution)<div class="ais-card px-4 py-3"><span class="text-[11px] font-bold" style="color:#00CFFF;">Solution: </span><span class="text-[13px]" style="color:#B6C8DD;"> {{ \Illuminate\Support\Str::limit(strip_tags($item->solution), 220) }}</span></div>@endif
                            @if($item->results)<div class="ais-card px-4 py-3"><span class="text-[11px] font-bold" style="color:#00CFFF;">Lessons: </span><span class="text-[13px]" style="color:#B6C8DD;"> {{ \Illuminate\Support\Str::limit(strip_tags($item->results), 220) }}</span></div>@endif
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="ais-card p-12 text-center text-sm" style="color:#B6C8DD;">No case studies match your filters.</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $items->links() }}</div>
    </div>
</section>

{{-- CTA --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="cs-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
        <p class="ais-eyebrow">Your scenario</p>
        <h2 id="cs-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Discuss your own scenario</h2>
        <p class="ais-sub mt-4 text-[13px] sm:text-sm">Tell us about your environment and we will propose a tailored assessment.</p>
        <a href="{{ route('contact') }}" class="ais-btn-primary mt-8">
            Start Your Project
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
