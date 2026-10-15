@extends('layouts.public')

@section('title', 'Blog — AISecurity')
@section('description', 'Insights on IT infrastructure, cybersecurity, cloud technology, and enterprise IT management.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="blog-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Blog</span>
        </nav>
        <p class="ais-eyebrow">Insights</p>
        <h1 id="blog-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">Insights and perspectives</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">Expert perspectives on IT infrastructure, cybersecurity, cloud technology, and digital transformation.</p>
    </div>
</section>

{{-- Posts grid --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Blog posts">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        @forelse($posts as $post)
        @if($loop->first)
        {{-- Featured first post --}}
        <a href="{{ route('blog.show', $post->slug) }}" class="ais-card overflow-hidden group grid lg:grid-cols-2 gap-0 mb-10 items-stretch">
            <div class="overflow-hidden aspect-[16/10] lg:aspect-auto lg:min-h-[320px]">
                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $post->title }}">
                @else
                <div class="w-full h-full min-h-[240px] flex items-center justify-center" style="background:rgba(255,255,255,.04);">
                    <span class="text-[11px] tracking-[0.24em]" style="color:#7FB7E8;">AISecurity Blog</span>
                </div>
                @endif
            </div>
            <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                <div class="text-[11px] tracking-[0.2em] mb-3" style="color:#7FB7E8;">{{ $post->category->name ?? 'General' }} · {{ $post->published_at?->format('M d, Y') }}</div>
                <h2 class="ais-h2 text-2xl sm:text-3xl">{{ $post->title }}</h2>
                <p class="mt-3 text-sm sm:text-base leading-relaxed" style="color:#B6C8DD;">{{ $post->excerpt }}</p>
                <span class="ais-link mt-5">
                    Read article
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </a>
        @endif
        @endforeach

        {{-- Rest of posts --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @forelse($posts as $post)
            @if(!$loop->first)
            <a href="{{ route('blog.show', $post->slug) }}" class="ais-card overflow-hidden group flex flex-col">
                <div class="overflow-hidden aspect-[16/10]">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $post->title }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center" style="background:rgba(255,255,255,.04);">
                        <span class="text-[11px] tracking-[0.24em]" style="color:#7FB7E8;">AISecurity Blog</span>
                    </div>
                    @endif
                </div>
                <div class="p-5 sm:p-6 flex flex-col flex-1">
                    <div class="text-[10px] tracking-[0.2em] mb-2" style="color:#7FB7E8;">{{ $post->category->name ?? 'General' }} · {{ $post->published_at?->format('M d, Y') }}</div>
                    <h3 class="text-lg font-bold tracking-tight text-white">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed line-clamp-2" style="color:#B6C8DD;">{{ $post->excerpt }}</p>
                    <span class="ais-link mt-4 text-sm">Read article
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </div>
            </a>
            @endif
            @empty
            <div class="ais-card col-span-full p-12 sm:p-20 text-center">
                <p class="text-sm" style="color:#B6C8DD;">No articles published yet. Check back soon.</p>
            </div>
            @endforelse
        </div>

        @if(method_exists($posts, 'links'))
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</section>

<x-ais-cta />

</div>
@endsection
