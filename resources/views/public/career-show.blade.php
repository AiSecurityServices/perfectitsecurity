@extends('layouts.public')

@section('title', $item->title . ' — Careers')
@section('description', $item->summary ?? $item->title)

@section('content')
<div class="ais-page">
<section class="relative w-full overflow-hidden" aria-labelledby="career-show-heading" style="background:#020916;">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 45% at 50% 0%, rgba(22,139,255,.14), transparent 65%);"></div>
    <div class="relative w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-18 pb-12 lg:pb-16">
        <a href="{{ route('careers') }}" class="ais-link text-sm">&larr; Back to Careers</a>
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <span class="ais-badge">{{ $item->department ?? 'Open role' }}</span>
            @if($item->location)<span class="ais-badge">{{ $item->location }}</span>@endif
            @if($item->employment_type)<span class="ais-badge">{{ str_replace('_', ' ', $item->employment_type) }}</span>@endif
        </div>
        <h1 id="career-show-heading" class="ais-h2 mt-4 text-3xl sm:text-5xl leading-tight">{{ $item->title }}</h1>
        @if($item->summary)<p class="ais-sub mt-4 text-base sm:text-lg leading-relaxed">{{ $item->summary }}</p>@endif
        @if($item->description)
        <div class="ais-card p-6 sm:p-8 mt-8">
            <p class="ais-eyebrow">About the role</p>
            <div class="mt-3 text-sm leading-relaxed whitespace-pre-line" style="color:#DCEBFF;">{{ $item->description }}</div>
        </div>
        @endif
        @if($item->requirements)
        <div class="ais-card p-6 sm:p-8 mt-4">
            <p class="ais-eyebrow">Requirements</p>
            <div class="mt-3 text-sm leading-relaxed whitespace-pre-line" style="color:#DCEBFF;">{{ $item->requirements }}</div>
        </div>
        @endif
        <div class="mt-8">
            <a href="{{ route('contact') }}" class="ais-btn-primary">Apply via Contact
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
</div>
@endsection
