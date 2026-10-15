@extends('layouts.public')

@section('title', 'Portfolio — AISecurity')
@section('description', 'Demo portfolio: sample IT, cybersecurity, cloud and software concept projects clearly labeled as demonstrations.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="portfolio-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Portfolio</span>
        </nav>
        <p class="ais-eyebrow">Selected work</p>
        <h1 id="portfolio-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">Our portfolio</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            A selection of engagements across cybersecurity, cloud, and software engineering.
        </p>
    </div>
</section>

<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Portfolio items">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <form method="GET" action="{{ route('portfolio.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-2.5 mb-10" role="search">
            <label for="portfolio-search" class="sr-only">Search projects</label>
            <input type="text" id="portfolio-search" name="search" value="{{ request('search') }}" placeholder="Search projects…"
                   class="ais-input sm:max-w-xs text-sm">
            <label for="portfolio-category" class="sr-only">Filter by category</label>
            <select name="category" id="portfolio-category" class="ais-input sm:max-w-xs text-sm">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <div class="flex gap-2.5">
                <button class="ais-btn-primary !py-2 !px-4 !text-xs">Filter</button>
                @if(request('search') || request('category'))
                    <a href="{{ route('portfolio.index') }}" class="ais-btn-ghost !py-2 !px-4 !text-xs">Clear</a>
                @endif
            </div>
        </form>

        @if($featured->isNotEmpty())
        <p class="ais-eyebrow mb-5">Featured</p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-12">
            @foreach($featured as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="ais-card group block p-6">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="ais-badge">{{ $item->category ?? 'Project' }}</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="#7FB7E8" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </div>
                <h3 class="text-lg font-bold tracking-tight text-white">{{ $item->title }}</h3>
                <p class="mt-2 text-sm leading-relaxed" style="color:#B6C8DD;">{{ $item->summary }}</p>
                @if($item->client_name)<p class="mt-3 text-[11px] tracking-wider" style="color:#7FB7E8;">Client: {{ $item->client_name }}</p>@endif
                <span class="ais-link mt-4 text-sm">Open casefile
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>
            @endforeach
        </div>
        @endif

        <p class="ais-eyebrow mb-5">All work</p>
        @if($items->isEmpty())
            <div class="ais-card p-12 text-center text-sm" style="color:#B6C8DD;">Portfolio items are being curated — check back soon.</div>
        @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($items as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="ais-card group block p-6">
                <div class="text-[10px] tracking-[0.2em] mb-3" style="color:#7FB7E8;">{{ $item->category ?? 'Project' }}</div>
                <h3 class="text-lg font-bold tracking-tight text-white">{{ $item->title }}</h3>
                <p class="mt-2 text-sm leading-relaxed" style="color:#B6C8DD;">{{ $item->summary }}</p>
                <span class="ais-link mt-4 text-sm">Open casefile
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $items->links() }}</div>
        @endif
    </div>
</section>

<x-ais-cta />

</div>
@endsection
