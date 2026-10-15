@extends('layouts.public')

@section('title', $item->title . ' — Case Study')
@section('description', $item->summary ?? $item->title)

@section('content')
<div class="ais-page">
<section class="relative w-full overflow-hidden" aria-labelledby="cs-show-heading" style="background:#020916;">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 45% at 50% 0%, rgba(22,139,255,.14), transparent 65%);"></div>
    <div class="relative w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-18 pb-12 lg:pb-16">
        <a href="{{ route('case-studies') }}" class="ais-link text-sm">&larr; Back to Case Studies</a>
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <span class="ais-badge">{{ $item->industry ?? 'Case Study' }}</span>
            @if($item->is_demo)<span class="ais-badge">Illustrative demo scenario</span>@endif
            @if($item->country)<span class="ais-badge">{{ $item->country }}</span>@endif
        </div>
        <h1 id="cs-show-heading" class="ais-h2 mt-4 text-3xl sm:text-5xl leading-tight">{{ $item->title }}</h1>
        @if($item->client_name)<p class="mt-3 text-[12px] tracking-wide" style="color:#7FB7E8;">Client: {{ $item->client_name }}</p>@endif
        @if($item->summary)<p class="ais-sub mt-4 text-base sm:text-lg leading-relaxed">{{ $item->summary }}</p>@endif
        <div class="mt-8 grid md:grid-cols-3 gap-4">
            @foreach([['Challenge', $item->challenge], ['Solution', $item->solution], ['Results', $item->results]] as [$heading, $body])
            @if($body)
            <div class="ais-card p-6 reveal">
                <p class="ais-eyebrow">{{ $heading }}</p>
                <div class="mt-3 text-sm leading-relaxed whitespace-pre-line" style="color:#DCEBFF;">{{ $body }}</div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="mt-8 pt-6 border-t border-white/10">
            <a href="{{ route('get-quote') }}" class="ais-link">Facing a similar challenge? Get a quote
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
</div>
@endsection
