@extends('layouts.public')

@section('title', 'Refund Policy — AISecurity')
@section('description', 'AISecurity refund policy for IT services, subscriptions, and professional services.')

@section('content')
<div class="ais-page ais-legal">

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="refund-hero-heading">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true" style="background:radial-gradient(ellipse 60% 55% at 72% 40%, rgba(22,139,255,.20), transparent 65%), radial-gradient(ellipse 45% 50% at 85% 70%, rgba(121,88,255,.14), transparent 60%), radial-gradient(ellipse 70% 45% at 15% 10%, rgba(0,207,255,.07), transparent 60%);"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-16 pb-10">
        <nav class="ais-crumb mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Refund Policy</span>
        </nav>
        <p class="ais-eyebrow">Refunds</p>
        <h1 id="refund-hero-heading" class="ais-h2 mt-3 text-3xl sm:text-4xl leading-tight">Refund Policy</h1>
        <p class="mt-4 text-[11px] tracking-wider" style="color:#7FB7E8;">Last updated September 8, 2026</p>
    </div>
</section>

<section class="relative w-full border-t border-white/5" style="background:#020916;" aria-label="Refund policy content">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="ais-card p-6 sm:p-8 lg:p-12">
            <div class="max-w-none space-y-8" style="color:#DCEBFF;">

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-2">Overview</h2>
                    <p class="text-sm leading-relaxed">
                        At AISecurity, we are committed to delivering high-quality IT services. This Refund Policy outlines the terms under which refunds may be requested for our services, subscriptions, and professional engagements.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Service-Based Refunds</h2>
                    <p class="text-sm leading-relaxed mb-3">
                        Refunds for professional IT services are handled on a case-by-case basis:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <li><strong class="text-white">Fixed-Scope Projects:</strong> If work has not commenced, a full refund minus any administrative processing fees (up to 5%) may be issued within 14 days of payment. If work has commenced, refunds are calculated proportionally based on completed milestones.</li>
                        <li><strong class="text-white">Retainer/Monthly Services:</strong> Monthly retainer services may be cancelled with 30 days' written notice. No partial-month refunds are issued for services already delivered.</li>
                        <li><strong class="text-white">Hourly Support:</strong> Billed hours already worked are non-refundable. Pre-paid hour blocks may be refunded on a pro-rata basis for unused hours within 30 days of purchase.</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Subscription Refunds</h2>
                    <p class="text-sm leading-relaxed">
                        For subscription-based services (e.g., managed IT support plans, monitoring services), refunds may be issued within the first 14 days of a new subscription if no services have been rendered. After 14 days, subscriptions follow a 30-day cancellation notice period with no retroactive refund.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Deposit &amp; Escrow Payments</h2>
                    <p class="text-sm leading-relaxed">
                        Deposits paid for project escrow are refundable in full only if the project has not been initiated by our team. Once work begins, the deposit covers initial mobilisation costs and is non-refundable. The remaining balance follows the proportional milestone refund structure outlined above.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Non-Refundable Items</h2>
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <li>Third-party software licenses purchased on behalf of the client</li>
                        <li>Cloud infrastructure costs already incurred</li>
                        <li>Domain registrations and SSL certificate purchases</li>
                        <li>Completed penetration testing and security audit reports</li>
                        <li>Training sessions already delivered</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">How to Request a Refund</h2>
                    <p class="text-sm leading-relaxed">
                        To request a refund, please contact our billing team at <strong class="text-white">billing@techsupport.com</strong> with your invoice number, reason for the refund request, and any relevant documentation. We will review your request within 5 business days and respond with our decision.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Refund Processing</h2>
                    <p class="text-sm leading-relaxed">
                        Approved refunds will be processed within 10 business days using the original payment method. For international transactions, processing times may vary based on your bank and region.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Dispute Resolution</h2>
                    <p class="text-sm leading-relaxed">
                        If you disagree with our refund decision, you may escalate the matter to our senior management team at <strong class="text-white">complaints@techsupport.com</strong>. We aim to resolve all disputes amicably within 20 business days.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-3 mt-8">Contact Information</h2>
                    <p class="text-sm leading-relaxed">
                        For any questions about this Refund Policy, please contact us at:<br>
                        <strong class="text-white">Email:</strong> billing@techsupport.com<br>
                        <strong class="text-white">Phone:</strong> +1 (555) 123-4567<br>
                        <strong class="text-white">Address:</strong> AISecurity, 123 Tech Avenue, San Francisco, CA 94105, USA
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<x-ais-cta />

</div>
@endsection
