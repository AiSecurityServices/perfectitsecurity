@extends('layouts.public')

@section('title', 'Careers — Join Our Global IT Team')
@section('description', 'Join AISecurity — a world-class IT services company. We are hiring cybersecurity experts, cloud engineers, developers, and IT support specialists across multiple countries.')

@section('content')
<div class="ais-page">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="careers-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Careers</span>
        </nav>
        <p class="ais-eyebrow">Open roles · Hiring</p>
        <h1 id="careers-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl lg:text-5xl leading-tight">
            Build the future of cybersecurity + cloud
        </h1>
        <p class="ais-sub mt-4 text-[14px] sm:text-[15px] leading-relaxed max-w-2xl">
            Join a global team of elite IT professionals protecting enterprises worldwide. We offer competitive compensation, remote flexibility, continuous learning, and the chance to work on cutting-edge infrastructure.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="#positions" class="ais-btn-primary">
                View Open Positions
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="#culture" class="ais-btn-ghost">
                Our Culture
            </a>
        </div>
    </div>
</section>

{{-- Why join us --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" id="culture" aria-labelledby="culture-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="max-w-3xl reveal">
            <p class="ais-eyebrow">Culture</p>
            <h2 id="culture-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Why top engineers choose us.</h2>
            <p class="ais-sub mt-3 text-[13px]">Life at AISecurity — distributed, technical, and growth-driven.</p>
        </div>

        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach([
                ['title' => 'Global Remote-First', 'desc' => 'Work from anywhere in the world. Our distributed team spans 15+ countries with flexible hours that respect your timezone.', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                ['title' => 'Cutting-Edge Tech', 'desc' => 'Work with AWS, Azure, Kubernetes, AI/ML security tools, and zero-trust architectures on enterprise-scale deployments.', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                ['title' => 'Continuous Learning', 'desc' => 'Annual certification budgets, internal tech talks, conference sponsorships, and a dedicated learning management system.', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['title' => 'Competitive Pay', 'desc' => 'Above-market salaries, performance bonuses, equity options for senior roles, and transparent compensation bands.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['title' => 'Health & Wellness', 'desc' => 'Comprehensive health insurance, mental health support, gym stipends, and generous paid time off across all regions.', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['title' => 'Career Growth', 'desc' => 'Clear promotion tracks, mentorship programmes, cross-team rotations, and leadership development for high performers.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
            ] as $benefit)
            <div class="ais-card p-7 sm:p-8 group">
                <div class="text-[11px] tracking-[0.24em] mb-4" style="color:#00CFFF;">{{ str_pad((string)($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                <span class="ais-icon mb-4" style="background:rgba(22,139,255,.18); color:#4DA3FF;" aria-hidden="true">
                    <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $benefit['icon'] }}"/></svg>
                </span>
                <h3 class="text-lg font-bold tracking-tight text-white">{{ $benefit['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed" style="color:#B6C8DD;">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Open positions --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" id="positions" aria-labelledby="positions-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16">
        <div class="max-w-3xl reveal">
            <p class="ais-eyebrow">Open positions</p>
            <h2 id="positions-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">Find your role.</h2>
            <p class="ais-sub mt-3 text-[13px]">We are always looking for exceptional talent to join our global team.</p>
        </div>

        <div class="mt-10 space-y-3 sm:space-y-4 max-w-4xl mx-auto">
            @foreach([
                ['title' => 'Senior Cybersecurity Analyst', 'dept' => 'Security Operations', 'location' => 'Remote / London / New York', 'type' => 'Full-time', 'salary' => '$90K – $140K', 'tags' => ['SOC', 'Incident Response', 'SIEM']],
                ['title' => 'Cloud Infrastructure Engineer', 'dept' => 'Cloud Architecture', 'location' => 'Remote / Singapore / Dubai', 'type' => 'Full-time', 'salary' => '$100K – $160K', 'tags' => ['AWS', 'Azure', 'Kubernetes']],
                ['title' => 'Full-Stack Developer', 'dept' => 'Engineering', 'location' => 'Remote / Dhaka / London', 'type' => 'Full-time', 'salary' => '$70K – $120K', 'tags' => ['Laravel', 'React', 'DevOps']],
                ['title' => 'IT Support Specialist', 'dept' => 'Technical Support', 'location' => 'Remote / Dhaka', 'type' => 'Full-time', 'salary' => '$30K – $55K', 'tags' => ['M365', 'Networking', 'Helpdesk']],
                ['title' => 'Penetration Tester', 'dept' => 'Security Assessment', 'location' => 'Remote / London', 'type' => 'Full-time', 'salary' => '$95K – $150K', 'tags' => ['Web App', 'Network', 'OSCP']],
                ['title' => 'Project Manager', 'dept' => 'Delivery', 'location' => 'Remote / London / New York', 'type' => 'Full-time', 'salary' => '$80K – $130K', 'tags' => ['Agile', 'PMP', 'Client Relations']],
            ] as $job)
            <div class="ais-card p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5 mb-2">
                        <h3 class="text-lg font-bold tracking-tight text-white">{{ $job['title'] }}</h3>
                        <span class="ais-badge">{{ $job['type'] }}</span>
                    </div>
                    <p class="text-sm mb-2" style="color:#B6C8DD;">{{ $job['dept'] }} · {{ $job['location'] }}</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($job['tags'] as $tag)
                        <span class="ais-badge">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center gap-4 flex-shrink-0">
                    <span class="text-sm font-bold" style="color:#00CFFF;">{{ $job['salary'] }}</span>
                    <a href="{{ route('contact') }}" class="ais-btn-primary !py-2 !px-4 !text-xs">
                        Apply Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-labelledby="careers-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
        <p class="ais-eyebrow">Open application</p>
        <h2 id="careers-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">
            Don't see your role?
        </h2>
        <p class="ais-sub mt-4 text-[13px] sm:text-sm">
            We are always interested in hearing from exceptional people. Send us your CV and tell us how you can contribute to our mission.
        </p>
        <a href="{{ route('contact') }}" class="ais-btn-primary mt-8">
            Send Open Application
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
