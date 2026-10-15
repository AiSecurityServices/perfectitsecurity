@extends('layouts.public')

@section('title', 'Resources — AISecurity')
@section('description', 'Curated cybersecurity tools, threat intelligence, and IT resources.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="links-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Resources</span>
        </nav>
        <p class="ais-eyebrow">Curated resources</p>
        <h1 id="links-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">Cybersecurity tools and resources</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">A curated collection of threat intelligence, security tools, and IT resources for professionals.</p>
    </div>
</section>

{{-- Links grid --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Curated resources">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @forelse($allLinks as $link)
            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="ais-card group block p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="ais-eyebrow mb-2" style="font-size:10px;">{{ $link->category }}</div>
                        <h3 class="font-semibold text-white text-sm mb-1">{{ $link->title }}</h3>
                        @if($link->description)
                        <p class="text-xs leading-relaxed line-clamp-2" style="color:#B6C8DD;">{{ $link->description }}</p>
                        @endif
                        <div class="mt-2 text-[10px] tracking-wider truncate" style="color:#7FB7E8;">{{ parse_url($link->url, PHP_URL_HOST) }}</div>
                    </div>
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="#7FB7E8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </div>
            </a>
            @empty
            <div class="ais-card col-span-full p-12 sm:p-20 text-center">
                <p class="text-sm" style="color:#B6C8DD;">No resources available yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Submit link --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" x-data="{ showForm: false }" aria-label="Submit a resource">
    <div class="w-full max-w-2xl mx-auto px-4 sm:px-6 py-12 lg:py-16">
        <div class="text-center mb-8">
            <p class="ais-eyebrow">Submit a resource</p>
            <h2 class="ais-h2 mt-3 text-2xl sm:text-3xl">Submit a resource</h2>
            <p class="ais-sub mt-2 text-sm sm:text-base">Know a great cybersecurity tool or IT resource? Share it with us.</p>
        </div>
        <div class="text-center mb-8">
            <button @click="showForm = !showForm" class="ais-btn-ghost">
                <span x-text="showForm ? 'Close' : 'Suggest a Resource'">Suggest a Resource</span>
            </button>
        </div>
        <div x-show="showForm" x-transition x-cloak style="display:none;">
            <form action="{{ route('useful-links.submit') }}" method="POST" class="ais-card p-6 space-y-5">
                @csrf
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-white mb-1.5">Your Name</label>
                        <input type="text" name="submitter_name" class="ais-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white mb-1.5">Email (optional)</label>
                        <input type="email" name="submitter_email" class="ais-input">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-white mb-1.5">Link Title *</label>
                    <input type="text" name="title" required class="ais-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-white mb-1.5">URL *</label>
                    <input type="url" name="url" required class="ais-input" placeholder="https://">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-white mb-1.5">Description</label>
                    <textarea name="description" rows="2" class="ais-input resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-white mb-1.5">Category</label>
                    <select name="category" class="ais-input">
                        <option value="cybersecurity">Cybersecurity</option>
                        <option value="tools">Tools</option>
                        <option value="monitoring">Monitoring</option>
                        <option value="cloud">Cloud</option>
                        <option value="general">General</option>
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                    <p class="text-xs" style="color:#7FB7E8;">Submissions reviewed before publishing.</p>
                    <button type="submit" class="ais-btn-primary !py-2 !px-4 !text-xs">Submit</button>
                </div>
            </form>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
