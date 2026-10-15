@extends('layouts.public')

@section('title', $article->title . ' — Knowledge Base | AISecurity')
@section('description', $article->excerpt ?? 'Technical runbook and guide for enterprise systems.')

@section('content')
<div class="ais-page">

{{-- Header --}}
<section class="relative w-full overflow-hidden border-b border-white/5" aria-labelledby="kb-article-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('kb.index') }}">Knowledge Base</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page" class="truncate max-w-xs">{{ $article->category->name ?? 'Article' }}</span>
        </nav>

        <div class="max-w-4xl">
            <p class="ais-eyebrow">{{ $article->category->name ?? 'Guide' }}</p>
            <h1 id="kb-article-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">
                {{ $article->title }}
            </h1>
            <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-[11px] tracking-wider" style="color:#7FB7E8;">
                <span>Published {{ $article->created_at?->format('M d, Y') }}</span>
                <span>~{{ max(1, ceil(str_word_count(strip_tags($article->content)) / 200)) }} min read</span>
                <span>{{ number_format($article->views_count) }} views</span>
            </div>
        </div>
    </div>
</section>

{{-- Content & Sidebar --}}
<section class="relative w-full" style="background:#020916;" aria-label="Article content">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-6 lg:gap-8">

            {{-- Main article body --}}
            <article class="lg:col-span-8 min-w-0 max-w-4xl">
                @if($article->excerpt)
                <div class="ais-card px-5 sm:px-6 py-5 mb-8 text-base leading-relaxed" style="color:#B6C8DD;">
                    {{ $article->excerpt }}
                </div>
                @endif

                <div class="ais-card p-6 sm:p-8 lg:p-10">
                    <div class="prose dark:prose-invert max-w-none" style="color:#DCEBFF;">
                        {!! $article->content !!}
                    </div>

                    @if($article->tags && $article->tags->isNotEmpty())
                    <div class="mt-10 pt-6 border-t border-white/5 flex flex-wrap items-center gap-2">
                        <span class="text-[10px] uppercase tracking-[0.2em] mr-2" style="color:#7FB7E8;">Tags</span>
                        @foreach($article->tags as $tag)
                        <span class="ais-badge">
                            #{{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Helpful vote --}}
                <div class="ais-card p-6 sm:p-8 mt-6 text-center" x-data="{ voted: false, helpful: null }">
                    <p class="ais-eyebrow mb-2">Your feedback</p>
                    <h3 class="text-xl font-bold text-white mb-2">Was this article helpful?</h3>
                    <p class="text-sm mb-6" style="color:#B6C8DD;">Your feedback helps us improve our documentation.</p>

                    <div x-show="!voted" class="flex flex-col sm:flex-row justify-center gap-3">
                        <button @click="vote(true)" class="ais-btn-primary">
                            Yes, helpful
                        </button>
                        <button @click="vote(false)" class="ais-btn-ghost">
                            Not helpful
                        </button>
                    </div>

                    <div x-show="voted" x-transition class="text-center" style="display:none;">
                        <p class="text-xs tracking-[0.18em]" style="color:#00CFFF;">Thank you for your feedback</p>
                    </div>

                    <script>
                    function vote(helpful) {
                        fetch('{{ route('kb.vote', $article->slug) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ helpful: helpful })
                        })
                        .then(r => r.json())
                        .then(data => { this.voted = true; this.helpful = helpful; })
                        .catch(() => { this.voted = true; });
                    }
                    </script>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-4 sm:space-y-5">
                @if(isset($related) && $related->isNotEmpty())
                <div class="ais-card p-6">
                    <p class="ais-eyebrow mb-4">Related guides</p>
                    <div class="space-y-4">
                        @foreach($related as $rel)
                        <a href="{{ route('kb.show', $rel->slug) }}" class="block group pb-4 border-b border-white/5 last:border-0 last:pb-0">
                            <h4 class="text-sm font-semibold text-white transition-colors line-clamp-2">
                                {{ $rel->title }}
                            </h4>
                            <span class="text-[10px] tracking-wider mt-1 block" style="color:#7FB7E8;">{{ $rel->updated_at?->diffForHumans() }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="ais-card p-6 text-center">
                    <p class="ais-eyebrow mb-3">Need help?</p>
                    <h4 class="text-base font-bold text-white mb-1">Need direct assistance?</h4>
                    <p class="text-xs leading-relaxed mb-4" style="color:#B6C8DD;">
                        Our Tier 3 engineers are on standby 24/7 to resolve technical incidents.
                    </p>
                    <a href="{{ route('contact') }}" class="ais-btn-primary w-full justify-center">
                        Open Support Ticket
                    </a>
                </div>
            </aside>

        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
