@extends('layouts.public')

@section('title', $item->title . ' — Portfolio')
@section('description', $item->summary ?? $item->title)

@section('content')
<div class="ais-page">
<section class="relative w-full overflow-hidden" aria-labelledby="portfolio-show-heading" style="background:#020916;">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 45% at 50% 0%, rgba(22,139,255,.14), transparent 65%);"></div>
    <div class="relative w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-18 pb-12 lg:pb-16">
        <a href="{{ route('portfolio.index') }}" class="ais-link text-sm">&larr; Back to Portfolio</a>
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <span class="ais-badge">{{ $item->category ?? 'Project' }}</span>
            @if($item->is_demo)<span class="ais-badge">Demo / sample profile</span>@endif
        </div>
        <h1 id="portfolio-show-heading" class="ais-h2 mt-4 text-3xl sm:text-5xl leading-tight">{{ $item->title }}</h1>
        @if($item->client_name)<p class="mt-3 text-[12px] tracking-wide" style="color:#7FB7E8;">Client: {{ $item->client_name }}</p>@endif
        @if($item->summary)<p class="ais-sub mt-4 text-base sm:text-lg leading-relaxed">{{ $item->summary }}</p>@endif
        @if($item->description)
        <div class="ais-card p-6 sm:p-8 mt-8">
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:#DCEBFF;">{{ $item->description }}</div>
        </div>
        @endif
        @if($item->project_url)
        <a href="{{ $item->project_url }}" target="_blank" rel="noopener" class="ais-btn-primary mt-6">Visit Live Project
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        @endif
        <div class="mt-8 pt-6 border-t border-white/10">
            <a href="{{ route('get-quote') }}" class="ais-link">Want results like these? Get a quote
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
</div>
@endsection
