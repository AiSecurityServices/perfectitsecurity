@extends('layouts.public')

@section('title', $post->title . ' — AISecurity Blog')
@section('description', $post->excerpt ?? 'Enterprise cybersecurity and IT infrastructure insights.')

@section('content')
<div class="ais-page">

{{-- Header --}}
<section class="relative w-full overflow-hidden border-b border-white/5" aria-labelledby="blog-show-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('blog.index') }}">Blog</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page" class="truncate max-w-xs">{{ $post->category->name ?? 'Article' }}</span>
        </nav>

        <div class="max-w-4xl">
            <p class="ais-eyebrow">{{ $post->category->name ?? 'Insights' }}</p>
            <h1 id="blog-show-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">
                {{ $post->title }}
            </h1>

            <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs" style="color:#7FB7E8;">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="border:1px solid rgba(0,207,255,.5); background:rgba(0,207,255,.1); color:#00CFFF;" aria-hidden="true">
                        {{ substr($post->author->name ?? 'Admin', 0, 1) }}
                    </div>
                    <span class="font-medium" style="color:#B6C8DD;">{{ $post->author->name ?? 'AISecurity Editorial' }}</span>
                </div>
                <span aria-hidden="true">·</span>
                <span class="text-[11px] tracking-wider">{{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</span>
                <span aria-hidden="true">·</span>
                <span class="text-[11px] tracking-wider">~{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} min read</span>
            </div>
        </div>
    </div>
</section>

{{-- Content body --}}
<section class="relative w-full" style="background:#020916;" aria-label="Article">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        @if($post->featured_image)
        <div class="ais-card overflow-hidden aspect-[16/9] mb-8">
            <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-full object-cover" alt="{{ $post->title }}">
        </div>
        @endif

        @if($post->excerpt)
        <div class="ais-card px-5 sm:px-6 py-5 mb-8 text-base sm:text-lg leading-relaxed" style="color:#B6C8DD;">
            {{ $post->excerpt }}
        </div>
        @endif

        <div class="ais-card p-6 sm:p-8 lg:p-10">
            <article class="prose dark:prose-invert max-w-none leading-relaxed" style="color:#DCEBFF;">
                {!! $post->content !!}
            </article>

            @if($post->tags && $post->tags->isNotEmpty())
            <div class="mt-10 pt-6 border-t border-white/5 flex flex-wrap items-center gap-2">
                <span class="text-[10px] uppercase tracking-[0.2em] mr-2" style="color:#7FB7E8;">Tags</span>
                @foreach($post->tags as $tag)
                <span class="ais-badge">
                    #{{ $tag->name }}
                </span>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Comments --}}
        <div class="ais-card p-6 sm:p-8 mt-6">
            <p class="ais-eyebrow mb-2">Discussion</p>
            <h3 class="ais-h2 text-2xl mb-6">Discussion &amp; comments</h3>

            @if(session('success'))
            <div class="ais-card mb-6 px-4 py-3 text-sm" role="status" style="border-color:rgba(0,207,255,.5); color:#DCEBFF;">
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <form action="{{ route('blog.comment', $post->slug) }}" method="POST" class="space-y-5">
                @csrf
                @guest
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-white mb-1.5">Your Name</label>
                        <input type="text" name="name" required class="ais-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white mb-1.5">Your Email</label>
                        <input type="email" name="email" required class="ais-input">
                    </div>
                </div>
                @endguest

                <div>
                    <label class="block text-xs font-semibold text-white mb-1.5">Your Message</label>
                    <textarea name="comment" rows="4" required placeholder="Share your perspective or feedback..." class="ais-input resize-none"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="ais-btn-primary">
                        Submit Comment for Review
                    </button>
                </div>
            </form>
        </div>

        {{-- Related posts --}}
        @if(isset($related) && $related->isNotEmpty())
        <div class="mt-12 pt-10 border-t border-white/5">
            <p class="ais-eyebrow mb-5">Related articles</p>
            <div class="grid sm:grid-cols-3 gap-4 sm:gap-5">
                @foreach($related as $rel)
                <a href="{{ route('blog.show', $rel->slug) }}" class="ais-card group block p-5">
                    <span class="ais-eyebrow mb-2 block" style="font-size:10px;">
                        {{ $rel->category->name ?? 'Article' }}
                    </span>
                    <h4 class="text-sm font-bold text-white line-clamp-2">
                        {{ $rel->title }}
                    </h4>
                    <span class="text-[10px] tracking-wider mt-2 block" style="color:#7FB7E8;">{{ $rel->published_at?->format('M d, Y') }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

<x-ais-cta />

</div>
@endsection
