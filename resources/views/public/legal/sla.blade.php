@extends('layouts.public')

@section('title', 'Service Level Agreement (SLA) — AISecurity')
@section('description', 'AISecurity service level agreement outlining response times, resolution targets, uptime guarantees, and escalation procedures.')

@section('content')
<div class="ais-page ais-legal">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="sla-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Service Level Agreement</span>
        </nav>
        <p class="ais-eyebrow">Service Commitments</p>
        <h1 id="sla-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">Service Level Agreement</h1>
        <p class="mt-4 text-[11px] tracking-wider" style="color:#7FB7E8;">Last updated September 8, 2026</p>
    </div>
</section>

<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="SLA content">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        {{-- SLA Tiers Overview --}}
        <dl class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-10">
            @foreach([
                ['tier' => 'Basic', 'response' => '4 hours', 'resolution' => '8 hours'],
                ['tier' => 'Standard', 'response' => '2 hours', 'resolution' => '4 hours'],
                ['tier' => 'Priority', 'response' => '1 hour', 'resolution' => '2 hours'],
                ['tier' => 'Critical', 'response' => '30 min', 'resolution' => '1 hour'],
            ] as $sla)
            <div class="ais-card px-5 py-6 text-center">
                <dt class="ais-eyebrow" style="font-size:11px;">{{ $sla['tier'] }}</dt>
                <dd class="mt-3 space-y-1.5 text-sm" style="color:#B6C8DD;">
                    <div><span class="text-[11px]" style="color:#7FB7E8;">Response</span> <strong class="text-white">{{ $sla['response'] }}</strong></div>
                    <div><span class="text-[11px]" style="color:#7FB7E8;">Resolution</span> <strong class="text-white">{{ $sla['resolution'] }}</strong></div>
                </dd>
            </div>
            @endforeach
        </dl>

        <div class="ais-card p-6 sm:p-8 lg:p-12">
            <div class="max-w-none space-y-8" style="color:#DCEBFF;">

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-2">Purpose</h2>
                    <p class="text-sm leading-relaxed">
                        This Service Level Agreement (SLA) defines the service standards, response times, resolution targets, and escalation procedures that AISecurity commits to when delivering IT support, managed services, and professional engagements to its clients.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Scope</h2>
                    <p class="text-sm leading-relaxed">
                        This SLA applies to all active service agreements between AISecurity and its clients. Specific SLA tiers may vary based on the client's service plan, contract terms, and priority level.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Response Time Definitions</h2>
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <li><strong class="text-white">Initial Response:</strong> The time between ticket submission and the first human acknowledgement from a support agent.</li>
                        <li><strong class="text-white">Resolution Time:</strong> The time between ticket submission and the point at which the issue is resolved or a permanent workaround is implemented.</li>
                        <li><strong class="text-white">Business Hours:</strong> Monday–Friday, 09:00–18:00 in the client's local timezone, unless 24/7 coverage is included in the service plan.</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Priority Classification</h2>
                    <div class="overflow-x-auto -mx-1 px-1">
                        <table class="ais-table">
                            <thead>
                                <tr>
                                    <th>Priority</th>
                                    <th>Description</th>
                                    <th>Examples</th>
                                    <th>Response</th>
                                    <th>Resolution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach([
                                    ['priority' => 'Critical', 'desc' => 'Complete system outage or security breach', 'examples' => 'Ransomware, production down, data breach', 'response' => '15 min', 'resolution' => '1 hour'],
                                    ['priority' => 'Urgent', 'desc' => 'Major functionality impaired', 'examples' => 'Email down, VPN failure, database issues', 'response' => '30 min', 'resolution' => '2 hours'],
                                    ['priority' => 'High', 'desc' => 'Significant impact on operations', 'examples' => 'Slow performance, partial outage', 'response' => '1 hour', 'resolution' => '4 hours'],
                                    ['priority' => 'Medium', 'desc' => 'Moderate impact, workaround available', 'examples' => 'Non-critical feature issues', 'response' => '2 hours', 'resolution' => '8 hours'],
                                    ['priority' => 'Low', 'desc' => 'Minor impact, cosmetic or informational', 'examples' => 'UI issues, documentation requests', 'response' => '4 hours', 'resolution' => '24 hours'],
                                ] as $p)
                                <tr>
                                    <td><span class="ais-badge">{{ $p['priority'] }}</span></td>
                                    <td class="text-sm">{{ $p['desc'] }}</td>
                                    <td class="text-xs" style="color:#7FB7E8;">{{ $p['examples'] }}</td>
                                    <td class="text-sm font-semibold text-white">{{ $p['response'] }}</td>
                                    <td class="text-sm font-semibold text-white">{{ $p['resolution'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Escalation Procedures</h2>
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <li><strong class="text-white">Level 1 (T+30 min):</strong> Automatic escalation to support team lead if no initial response within SLA.</li>
                        <li><strong class="text-white">Level 2 (T+60 min):</strong> Escalation to support manager with management notification.</li>
                        <li><strong class="text-white">Level 3 (T+2 hours):</strong> Escalation to CTO/Director with client executive notification.</li>
                        <li><strong class="text-white">Emergency Bridge:</strong> For critical incidents, a dedicated bridge call is established within 15 minutes with all stakeholders.</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Uptime Guarantee</h2>
                    <p class="text-sm leading-relaxed">
                        For managed services clients, AISecurity guarantees a minimum of <strong class="text-white">99.9% uptime</strong> for supported systems during each calendar month. Uptime is measured excluding scheduled maintenance windows (communicated 48 hours in advance) and force majeure events.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">SLA Credits</h2>
                    <p class="text-sm leading-relaxed">
                        If SLA targets are not met, clients may be eligible for service credits:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm mt-2">
                        <li>99.0%–99.9% uptime: 5% credit on monthly service fee</li>
                        <li>95.0%–99.0% uptime: 10% credit on monthly service fee</li>
                        <li>Below 95.0% uptime: 25% credit on monthly service fee</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Exclusions</h2>
                    <p class="text-sm leading-relaxed">
                        This SLA does not cover: client-caused outages, third-party service provider failures, natural disasters, internet service provider outages, scheduled maintenance (with advance notice), or issues outside the agreed scope of support.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Reporting</h2>
                    <p class="text-sm leading-relaxed">
                        AISecurity provides monthly SLA compliance reports to all managed services clients. Reports include response times, resolution times, uptime metrics, ticket volumes, and SLA breach analysis.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Contact</h2>
                    <p class="text-sm leading-relaxed">
                        For SLA-related inquiries, please contact your account manager or email <strong class="text-white">sla@techsupport.com</strong>.
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
