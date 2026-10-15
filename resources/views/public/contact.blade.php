@extends('layouts.public')

@section('title', 'Direct Infrastructure & SOC Contact — AISecurity')
@section('description', 'Connect with our senior cybersecurity engineers, solutions architects, and 24/7 technical operations center.')

@section('content')
<div class="ais-page">

<x-ais-page-hero
    eyebrow="Contact Us"
    title="Connect Directly with <span class='ais-title-hl'>Our Engineering Team</span>"
    lede="Whether requesting an immediate threat assessment, migrating cloud infrastructure, or configuring enterprise SLA support, our team is standing by."
    :chips="[
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M18 19a4 4 0 01-4 3h-2M4 13a8 8 0 0116 0M3 15a2 2 0 012-2h1v6H5a2 2 0 01-2-2v-2zM21 15a2 2 0 00-2-2h-1v6h1a2 2 0 002-2v-2z\'/>', '24/7 Support'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud'],
    ]"
>
    <x-slot:breadcrumbs>
        <nav class="ais-crumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Contact</span></nav>
    </x-slot:breadcrumbs>
    <x-slot:actions>
        <a href="{{ route('get-quote') }}" class="ais-btn-primary">Get a Free Quote
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        <a href="{{ route('services.index') }}" class="ais-btn-ghost">Browse Services</a>
    </x-slot:actions>
</x-ais-page-hero>

{{-- ═════════ CONTACT GRID ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-label="Contact form and channels" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-6 lg:gap-8">

            {{-- Form column --}}
            <div class="lg:col-span-8">
                <div class="ais-card p-6 sm:p-8 reveal">
                    <p class="ais-eyebrow">Consultation Request</p>
                    <h2 class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">Initiate technical consultation</h2>
                    <p class="ais-sub mt-3 text-[13px]">Fill out the parameters below and a principal architect will reach out within 2 hours.</p>

                    @if(session('success'))
                    <div class="mt-6 ais-glass px-4 py-3 text-sm font-semibold text-white" style="border-color:rgba(0,207,255,.45);" role="status">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="mt-6 ais-glass px-4 py-3 text-sm" style="border-color:rgba(255,92,92,.5);" role="alert">
                        <p class="font-bold mb-1 text-white">Please correct the following:</p>
                        <ul class="list-disc list-inside space-y-0.5" style="color:#FFB3B3;">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="mt-8 space-y-6">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="mb-1.5 block text-[13px] font-semibold text-white" for="contact-name">Full Name *</label>
                                <input type="text" id="contact-name" name="name" value="{{ old('name') }}" required
                                       class="ais-input" @error('name') aria-invalid="true" @enderror>
                                @error('name') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-[13px] font-semibold text-white" for="contact-email">Corporate Email *</label>
                                <input type="email" id="contact-email" name="email" value="{{ old('email') }}" required
                                       class="ais-input" @error('email') aria-invalid="true" @enderror>
                                @error('email') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="mb-1.5 block text-[13px] font-semibold text-white" for="contact-company">Company / Organization</label>
                                <input type="text" id="contact-company" name="company" value="{{ old('company') }}" placeholder="Acme Enterprise"
                                       class="ais-input">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-[13px] font-semibold text-white" for="contact-phone">Direct Phone</label>
                                <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000"
                                       class="ais-input">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[13px] font-semibold text-white" for="contact-subject">Primary Consultation Focus *</label>
                            <select name="subject" id="contact-subject" class="ais-input">
                                <option value="cybersecurity">Cybersecurity Architecture &amp; Penetration Audit</option>
                                <option value="managed-it">Managed IT Infrastructure &amp; 24/7 SOC</option>
                                <option value="cloud">Cloud Migration &amp; Multi-Cloud DevOps</option>
                                <option value="general">Custom Enterprise Work Order / Quote</option>
                                <option value="support">Active Contract SLA Escalation</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[13px] font-semibold text-white" for="contact-message">Project Scope &amp; Technical Details *</label>
                            <textarea name="message" id="contact-message" rows="5" required
                                      placeholder="Provide an overview of your current infrastructure, timeline, user count, or specific threat vectors..."
                                      class="ais-input resize-none" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                            @error('message') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button type="submit" class="ais-btn-primary">
                                Submit Request to Engineering
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                            <p class="mt-3 text-[12px]" style="color:#7FB7E8;">We only use your details to respond to your enquiry — no marketing, no sharing.</p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar column --}}
            <div class="lg:col-span-4 space-y-4 sm:space-y-5">
                <div class="ais-card p-7 sm:p-8 reveal delay-100">
                    <span class="ais-icon" style="background:rgba(0,207,255,.12); color:#00CFFF; border-color:rgba(0,207,255,.4);" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                    <h3 class="mt-4 text-[15px] font-bold text-white leading-snug">Critical incident response</h3>
                    <p class="mt-2 text-[12.5px] leading-relaxed" style="color:#B6C8DD;">
                        Under active attack or experiencing severe downtime? Our SOC tier-1 dispatch hotline is active 24/7/365 with immediate engineer escalation.
                    </p>
                    <div class="mt-5 ais-glass px-4 py-3 text-sm font-bold tracking-wider" style="border-color:rgba(0,207,255,.45); color:#00CFFF;">
                        +1 (800) 555-TECH-SOC
                    </div>
                </div>

                <div class="ais-card p-7 sm:p-8 reveal delay-200">
                    <span class="ais-icon" style="background:rgba(121,88,255,.14); color:#9275FF; border-color:rgba(121,88,255,.45);" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                    <h3 class="mt-4 text-[15px] font-bold text-white leading-snug">Direct channels</h3>
                    <dl class="mt-4 space-y-4">
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] mb-1" style="color:#7FB7E8;">Corporate dispatch</dt>
                            <dd class="text-[14px] font-bold text-white">ops@techsupportsolutions.com</dd>
                        </div>
                        <div class="pt-4 border-t border-white/10">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] mb-1" style="color:#7FB7E8;">Guaranteed response</dt>
                            <dd class="text-sm font-bold" style="color:#00CFFF;">Under 15 minutes for P1 tickets</dd>
                        </div>
                        <div class="pt-4 border-t border-white/10">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] mb-1" style="color:#7FB7E8;">Headquarters</dt>
                            <dd class="text-[12.5px]" style="color:#B6C8DD;">Level 42, Cyber Tower One, Technology Corridor</dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
