@extends('layouts.public')

@section('title', 'Terms of Service — AISecurity')
@section('description', 'Terms and conditions governing use of AISecurity services, customer portal, and IT consulting agreements.')

@section('content')
<div class="ais-page ais-legal">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="terms-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Terms of Service</span>
        </nav>
        <p class="ais-eyebrow">Legal Agreement</p>
        <h1 id="terms-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">Terms of Service</h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            These terms establish the rules, guidelines, and contractual obligations between your organization and AISecurity when accessing our portal and engaging our managed IT services.
        </p>
        <div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] tracking-wider" style="color:#7FB7E8;">
            <span>Effective September 2026</span>
            <span aria-hidden="true">·</span>
            <span>Version 2.8</span>
        </div>
    </div>
</section>

{{-- Content body --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Terms of service content">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="ais-card p-6 sm:p-8 lg:p-10">
            <div class="max-w-none space-y-10" style="color:#DCEBFF;">

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-2">1. Acceptance of Terms</h2>
                    <p class="leading-relaxed">
                        By registering an account, utilizing the AISecurity customer portal, or contracting for IT support, infrastructure engineering, or cybersecurity consulting, you agree to be legally bound by these Terms of Service and all incorporated service schedules.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">2. Provision of Managed Services</h2>
                    <p class="leading-relaxed mb-4">
                        AISecurity agrees to deliver services in accordance with the specifications outlined in relevant Work Orders, Quotations, and Master Services Agreements (MSAs). Service availability, target resolution times, and priority classifications are governed by our Service Level Agreement (SLA).
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">3. Account Authentication &amp; Security</h2>
                    <p class="leading-relaxed mb-4">
                        Users are responsible for safeguarding portal credentials. Multi-factor authentication (email OTP or SMS OTP) is mandatory for authorized administrative actions. You must immediately notify our response team of any suspected credential compromises or security incidents affecting your account.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">4. Invoicing, Payments &amp; Work Orders</h2>
                    <p class="leading-relaxed mb-4">
                        All fees, quotes, and retainer amounts are defined in designated quotations and invoice summaries. Work order modifications or expedited response requests outside agreed SLAs may incur standard engineering rates upon prior written customer authorization.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">5. Intellectual Property &amp; Confidentiality</h2>
                    <p class="leading-relaxed mb-4">
                        Each party maintains full ownership of its pre-existing intellectual property. AISecurity maintains strict confidentiality regarding client network topologies, server credentials, codebases, and corporate data discovered during technical maintenance.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-white mb-4 mt-10">6. Limitation of Liability</h2>
                    <p class="leading-relaxed">
                        To the maximum extent permitted by applicable law, neither party shall be liable for indirect, incidental, punitive, or consequential damages resulting from downtime, force majeure events, or external cyber attacks beyond commercially reasonable security controls.
                    </p>
                </div>

                <div class="ais-card p-6 mt-10">
                    <h3 class="text-lg font-bold text-white mb-2">Need a custom enterprise agreement?</h3>
                    <p class="text-sm mb-4" style="color:#B6C8DD;">
                        For enterprise-grade Master Services Agreements (MSAs) or tailored SLA commitments, speak to our legal operations department.
                    </p>
                    <a href="{{ route('contact') }}" class="ais-link text-sm">
                        Inquire About Enterprise Terms
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
