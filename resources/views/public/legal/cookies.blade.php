@extends('layouts.public')

@section('title', 'Cookie Policy — AISecurity')
@section('description', 'Information regarding how AISecurity uses cookies, sessions, and telemetry on our website and customer portal.')

@section('content')
<div class="ais-page ais-legal">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="cookies-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Cookie Policy</span>
        </nav>
        <p class="ais-eyebrow">Transparency</p>
        <h1 id="cookies-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">Cookie Policy</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            This document clarifies how we deploy cookies, local browser storage, and related tracking technologies to provide secure authentication, maintain session states, and monitor performance.
        </p>
        <div class="mt-6 text-[11px] tracking-wider" style="color:#7FB7E8;">
            <span>Updated September 2026</span>
        </div>
    </div>
</section>

{{-- Content body --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Cookie policy content">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="ais-card p-6 sm:p-8 lg:p-10">
            <div class="max-w-none space-y-10" style="color:#DCEBFF;">

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-2">1. What are Cookies?</h2>
                    <p class="leading-relaxed">
                        Cookies are small text records transferred to and stored in your browser by websites you visit. They enable platforms to remember login state, verify CSRF tokens, retain user preferences, and preserve security safeguards.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">2. Categories of Cookies We Use</h2>
                    <div class="space-y-4 mt-4">
                        <div class="ais-card p-5">
                            <p class="ais-eyebrow mb-2" style="font-size:10px;">Essential cookies</p>
                            <p class="text-sm" style="color:#B6C8DD;">
                                Required for platform operation, secure authentication (`techsupport_session`), and Cross-Site Request Forgery mitigation (`XSRF-TOKEN`). The portal cannot function securely without these tokens.
                            </p>
                        </div>

                        <div class="ais-card p-5">
                            <p class="ais-eyebrow mb-2" style="font-size:10px;">Functional cookies</p>
                            <p class="text-sm" style="color:#B6C8DD;">
                                Stores your interface preferences, such as selected dark/light mode and internationalization options (`NEXT_LOCALE`).
                            </p>
                        </div>

                        <div class="ais-card p-5">
                            <p class="ais-eyebrow mb-2" style="font-size:10px;">Performance cookies</p>
                            <p class="text-sm" style="color:#B6C8DD;">
                                Anonymously captures application errors and performance bottlenecks to support real-time system health checks and high service availability.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">3. Managing Your Cookie Preferences</h2>
                    <p class="leading-relaxed">
                        You can configure your browser to reject cookies or notify you when a cookie is placed. However, disabling essential session and CSRF tokens will prevent you from authenticating or submitting requests through the customer portal.
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
