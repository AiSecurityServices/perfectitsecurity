@extends('layouts.public')

@section('title', 'Knowledge Base & Technical Guides — AISecurity')
@section('description', 'Comprehensive technical documentation, cybersecurity runbooks, infrastructure guides, and FAQs.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="kb-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Knowledge Base</span>
        </nav>
        <p class="ais-eyebrow">Runbooks + Guides</p>
        <h1 id="kb-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">Technical guides &amp; runbooks</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            Explore architectural blueprints, incident response playbooks, and troubleshooting guides prepared by our senior engineering staff.
        </p>

        {{-- Search --}}
        <form action="{{ route('kb.index') }}" method="GET" role="search" class="mt-8 flex flex-col sm:flex-row gap-2.5 max-w-xl">
            @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <label for="kb-search" class="sr-only">Search articles, security guides, or error codes</label>
            <input type="text" id="kb-search" name="search" value="{{ request('search') }}" placeholder="Search articles, guides, error codes…"
                   class="ais-input flex-1 text-sm">
            <button type="submit" class="ais-btn-primary flex-shrink-0">Search
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </form>
    </div>
</section>

{{-- Main content --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Knowledge base articles">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">

        {{-- Category filter --}}
        @if(isset($categories) && $categories->isNotEmpty())
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-10" role="navigation" aria-label="Filter by topic">
            <a href="{{ route('kb.index', array_filter(['search' => request('search')])) }}"
               class="ais-badge flex-shrink-0" @if(!request('category')) style="border-color:#00CFFF;background:rgba(0,207,255,.12);color:#fff;" @endif>
                All topics ({{ $categories->sum('articles_count') }})
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('kb.index', array_filter(['category' => $cat->id, 'search' => request('search')])) }}"
               class="ais-badge flex-shrink-0" @if(request('category') == $cat->id) style="border-color:#00CFFF;background:rgba(0,207,255,.12);color:#fff;" @endif>
                {{ $cat->name }} ({{ $cat->articles_count }})
            </a>
            @endforeach
        </div>
        @endif

        {{-- Articles grid --}}
        @if($articles->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($articles as $article)
            <a href="{{ route('kb.show', $article->slug) }}" class="ais-card group p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <span class="ais-badge">{{ $article->category->name ?? 'Infrastructure' }}</span>
                        <span class="text-[11px]" style="color:#7FB7E8;">{{ number_format($article->views_count ?? 0) }} views</span>
                    </div>

                    <h3 class="text-lg font-bold tracking-tight text-white leading-snug">
                        {{ $article->title }}
                    </h3>

                    <p class="mt-2 text-sm leading-relaxed line-clamp-3" style="color:#B6C8DD;">
                        {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                    </p>
                </div>

                <div class="flex items-center justify-between gap-3 pt-4 mt-5 border-t border-white/5 text-[11px]">
                    <span style="color:#7FB7E8;">Updated {{ $article->updated_at?->diffForHumans() ?? 'recently' }}</span>
                    <span class="ais-link text-xs">Read guide
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $articles->withQueryString()->links() }}
        </div>
        @else
        <div class="ais-card p-12 sm:p-16 text-center max-w-lg mx-auto">
            <h3 class="text-lg font-bold text-white mb-2">No matching documentation found</h3>
            <p class="text-sm mb-6" style="color:#B6C8DD;">Try broadening your search query or removing the active category filter.</p>
            <a href="{{ route('kb.index') }}" class="ais-btn-ghost">Clear search filter</a>
        </div>
        @endif

    </div>
</section>

<x-ais-cta />

</div>
@endsection
