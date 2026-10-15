@extends('layouts.public')

@section('title', 'Get a Quote — AISecurity')
@section('description', 'Request a custom quote for IT services, cybersecurity, cloud solutions, and managed services. Free consultation with no obligation.')

@section('content')
<div class="ais-page">

<x-ais-page-hero
    eyebrow="Get a Quote"
    title="Get a <span class='ais-title-hl'>Custom Quote</span>"
    lede="Tell us about your requirements and our solutions team will prepare a tailored proposal with transparent pricing. Free consultation, no obligation."
    :chips="[
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 9h6v6H9zM4 4h5v5H4zM15 4h5v5h-5zM4 15h5v5H4zM15 15h5v5h-5z\'/>', 'AI Agents'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud'],
        ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity'],
    ]"
>
    <x-slot:breadcrumbs>
        <nav class="ais-crumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">Get a Quote</span></nav>
    </x-slot:breadcrumbs>
    <x-slot:actions>
        <a href="{{ route('contact') }}" class="ais-btn-primary">Talk to an Expert
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
        <a href="{{ route('pricing') }}" class="ais-btn-ghost">See Pricing Plans</a>
    </x-slot:actions>
</x-ais-page-hero>

{{-- ═════════ QUOTE FORM ═════════ --}}
<section class="relative w-full border-t border-white/5" aria-label="Quote request" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-6 lg:gap-8">

            {{-- Form --}}
            <div class="lg:col-span-7">
                <div class="ais-card p-6 sm:p-8 reveal">
                    <p class="ais-eyebrow">Quote Parameters</p>
                    <h2 class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">Describe your requirements</h2>

                    @if(session('success'))
                    <div class="mt-6 ais-glass px-4 py-3 text-sm font-semibold text-white" style="border-color:rgba(0,207,255,.45);" role="status">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('get-quote.submit') }}" method="POST" class="mt-8 space-y-6">
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="mb-1.5 block text-[13px] font-semibold text-white">Full Name *</label>
                                <input type="text" id="name" name="name" required
                                       class="ais-input"
                                       placeholder="John Smith" value="{{ old('name') }}" @error('name') aria-invalid="true" @enderror>
                                @error('name') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="mb-1.5 block text-[13px] font-semibold text-white">Email Address *</label>
                                <input type="email" id="email" name="email" required
                                       class="ais-input"
                                       placeholder="john@company.com" value="{{ old('email') }}" @error('email') aria-invalid="true" @enderror>
                                @error('email') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="phone" class="mb-1.5 block text-[13px] font-semibold text-white">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       class="ais-input"
                                       placeholder="+1 (555) 123-4567" value="{{ old('phone') }}">
                            </div>
                            <div>
                                <label for="company" class="mb-1.5 block text-[13px] font-semibold text-white">Company Name</label>
                                <input type="text" id="company" name="company"
                                       class="ais-input"
                                       placeholder="Acme Corp" value="{{ old('company') }}">
                            </div>
                        </div>

                        <div>
                            <label for="service_interest" class="mb-1.5 block text-[13px] font-semibold text-white">Service of Interest</label>
                            <select id="service_interest" name="service_interest" class="ais-input">
                                <option value="">Select a service category</option>
                                <option value="ai-agents">AI Agent Development</option>
                                <option value="ai-platform">AI Platform Setup &amp; Configuration</option>
                                <option value="ai-support">AI Customer Support Solutions</option>
                                <option value="ai-automation">Business Process Automation with AI</option>
                                <option value="ai-security">AI Security Review</option>
                                <option value="cybersecurity">Cybersecurity &amp; Penetration Testing</option>
                                <option value="managed-it">Managed IT Support</option>
                                <option value="cloud">Cloud Infrastructure &amp; Migration</option>
                                <option value="web-dev">Web Development</option>
                                <option value="software-dev">Software Development</option>
                                <option value="crm-erp">CRM / ERP Development</option>
                                <option value="mobile">Mobile App Development</option>
                                <option value="consulting">IT Consulting</option>
                                <option value="training">IT Training</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="service_id" class="mb-1.5 block text-[13px] font-semibold text-white">Specific Service (optional)</label>
                                <select id="service_id" name="service_id"
                                        class="ais-input" @error('service_id') aria-invalid="true" @enderror>
                                    <option value="">General enquiry</option>
                                    @isset($services)
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ (string) old('service_id', $selectedServiceId ?? '') === (string) $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('service_id') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="country_id" class="mb-1.5 block text-[13px] font-semibold text-white">Country (for local pricing &amp; tax)</label>
                                <select id="country_id" name="country_id"
                                        class="ais-input" @error('country_id') aria-invalid="true" @enderror>
                                    <option value="">Select country</option>
                                    @isset($countries)
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }} ({{ $country->currency_code }})</option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('country_id') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="budget_range" class="mb-1.5 block text-[13px] font-semibold text-white">Estimated Budget</label>
                                <select id="budget_range" name="budget_range"
                                        class="ais-input">
                                    <option value="">Select budget range</option>
                                    <option value="under-5k">Under $5,000</option>
                                    <option value="5k-15k">$5,000 - $15,000</option>
                                    <option value="15k-50k">$15,000 - $50,000</option>
                                    <option value="50k-100k">$50,000 - $100,000</option>
                                    <option value="100k-plus">$100,000+</option>
                                    <option value="not-sure">Not sure yet</option>
                                </select>
                            </div>
                            <div>
                                <label for="timeline" class="mb-1.5 block text-[13px] font-semibold text-white">Project Timeline</label>
                                <select id="timeline" name="timeline"
                                        class="ais-input">
                                    <option value="">Select timeline</option>
                                    <option value="urgent">Urgent (ASAP)</option>
                                    <option value="1-month">Within 1 month</option>
                                    <option value="3-months">Within 3 months</option>
                                    <option value="6-months">Within 6 months</option>
                                    <option value="exploring">Just exploring</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="mb-1.5 block text-[13px] font-semibold text-white">Project Requirements *</label>
                            <textarea id="message" name="message" rows="6" required
                                      class="ais-input resize-none"
                                      placeholder="Please describe your project requirements, challenges, and any specific needs..." @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                            @error('message') <p class="mt-1.5 text-xs" style="color:#FF8A8A;">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button type="submit" class="ais-btn-primary w-full sm:w-auto justify-center">
                                Submit Quote Request
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                            <p class="mt-3 text-[12px]" style="color:#7FB7E8;">We only use your details to prepare your quote — no marketing, no sharing.</p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-5 space-y-4 sm:space-y-5">
                <div class="ais-card p-7 sm:p-8 reveal delay-100">
                    <p class="ais-eyebrow">How It Works</p>
                    <div class="mt-5 space-y-5">
                        @foreach([
                            ['step' => '01', 'title' => 'Requirements Review', 'desc' => 'Our solutions team reviews your requirements within 24 hours.'],
                            ['step' => '02', 'title' => 'Free Consultation', 'desc' => 'We schedule a call to discuss your needs in detail and clarify scope.'],
                            ['step' => '03', 'title' => 'Custom Proposal', 'desc' => 'You receive a detailed proposal with transparent pricing and timeline.'],
                            ['step' => '04', 'title' => 'Project Kickoff', 'desc' => 'Once approved, we begin work with regular progress updates.'],
                        ] as $item)
                        <div class="flex gap-4">
                            <span class="flex-shrink-0 w-7 h-7 rounded-full inline-flex items-center justify-center text-[11px] font-extrabold" style="color:#00CFFF; background:rgba(0,207,255,.12); border:1px solid rgba(0,207,255,.5);" aria-hidden="true">{{ $item['step'] }}</span>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $item['title'] }}</h4>
                                <p class="text-xs leading-relaxed mt-1" style="color:#B6C8DD;">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="ais-card p-7 sm:p-8 reveal delay-200">
                    <p class="ais-eyebrow">Prefer to Talk?</p>
                    <div class="mt-4 space-y-3 text-sm">
                        <a href="tel:+15551234567" class="flex items-center gap-3 transition-colors hover:text-white" style="color:#B6C8DD;">
                            <span class="ais-icon !w-8 !h-8" style="background:rgba(0,207,255,.12); color:#00CFFF; border-color:rgba(0,207,255,.4);" aria-hidden="true"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span> +1 (555) 123-4567
                        </a>
                        <a href="mailto:info@techsupport.com" class="flex items-center gap-3 transition-colors hover:text-white" style="color:#B6C8DD;">
                            <span class="ais-icon !w-8 !h-8" style="background:rgba(121,88,255,.14); color:#9275FF; border-color:rgba(121,88,255,.45);" aria-hidden="true"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span> info@techsupport.com
                        </a>
                        <div class="flex items-center gap-3" style="color:#B6C8DD;">
                            <span class="ais-icon !w-8 !h-8" style="background:rgba(0,207,255,.12); color:#00CFFF; border-color:rgba(0,207,255,.4);" aria-hidden="true"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span> Response within 24 hours
                        </div>
                    </div>
                </div>

                <div class="ais-card p-7 sm:p-8 reveal delay-200">
                    <p class="ais-eyebrow">At a Glance</p>
                    <dl class="mt-5 grid grid-cols-2 gap-4 text-center">
                        <div class="ais-glass px-3 py-4">
                            <dt class="sr-only">Clients worldwide</dt>
                            <dd class="text-2xl font-extrabold text-white">250+</dd>
                            <dd class="mt-1 text-[11px]" style="color:#7FB7E8;">Clients worldwide</dd>
                        </div>
                        <div class="ais-glass px-3 py-4">
                            <dt class="sr-only">Uptime SLA</dt>
                            <dd class="text-2xl font-extrabold" style="color:#00CFFF;">99.9%</dd>
                            <dd class="mt-1 text-[11px]" style="color:#7FB7E8;">Uptime SLA</dd>
                        </div>
                        <div class="ais-glass px-3 py-4">
                            <dt class="sr-only">Critical response</dt>
                            <dd class="text-2xl font-extrabold text-white">15min</dd>
                            <dd class="mt-1 text-[11px]" style="color:#7FB7E8;">Critical response</dd>
                        </div>
                        <div class="ais-glass px-3 py-4">
                            <dt class="sr-only">Emergency support</dt>
                            <dd class="text-2xl font-extrabold" style="color:#00CFFF;">24/7</dd>
                            <dd class="mt-1 text-[11px]" style="color:#7FB7E8;">Emergency support</dd>
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
