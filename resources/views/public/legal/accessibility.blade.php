@extends('layouts.public')

@section('title', 'Accessibility Statement — AISecurity')
@section('description', 'AISecurity accessibility commitment, compliance goals, and digital inclusion standards.')

@section('content')
<div class="ais-page ais-legal">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="a11y-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Accessibility</span>
        </nav>
        <p class="ais-eyebrow">Digital Inclusion</p>
        <h1 id="a11y-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">Accessibility Statement</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            AISecurity is committed to ensuring digital accessibility for people of all abilities. We continuously improve our user experience and apply relevant accessibility standards.
        </p>
        <div class="mt-6 text-[11px] tracking-wider" style="color:#7FB7E8;">
            <span>Standard: WCAG 2.1 Level AA</span>
        </div>
    </div>
</section>

{{-- Content body --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Accessibility statement content">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="ais-card p-6 sm:p-8 lg:p-10">
            <div class="max-w-none space-y-10" style="color:#DCEBFF;">

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-2">Our Commitment</h2>
                    <p class="leading-relaxed">
                        We strive to adhere to the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA standards. These guidelines outline best practices to ensure digital web content is Perceivable, Operable, Understandable, and Robust for all users, including individuals using screen readers, keyboard-only navigation, and high-contrast display settings.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">Key Accessibility Features Implemented</h2>
                    <ul class="list-disc pl-6 space-y-2">
                        <li><strong class="text-white">Keyboard Navigation:</strong> All key interactive buttons, dropdowns, forms, and dialogs are fully navigable via standard keyboard focus rings.</li>
                        <li><strong class="text-white">Semantic HTML:</strong> Valid heading hierarchies (`h1` through `h6`), ARIA landmark regions, and descriptive form label associations.</li>
                        <li><strong class="text-white">Contrast &amp; Theming:</strong> Support for dark and light color modes calibrated to meet or exceed minimum contrast ratio thresholds.</li>
                        <li><strong class="text-white">Screen Reader Support:</strong> Informative alternative text attributes on iconography, graphics, and interactive widgets.</li>
                    </ul>
                </div>

                <div class="ais-card p-6 mt-10">
                    <h3 class="text-lg font-bold text-white mb-2">Feedback &amp; assistance</h3>
                    <p class="text-sm mb-4" style="color:#B6C8DD;">
                        If you experience difficulty accessing any part of our website or customer portal, please contact us with details about the issue and the assistive technology you are utilizing.
                    </p>
                    <a href="{{ route('contact') }}" class="ais-link text-sm">
                        Report an Accessibility Barrier
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
