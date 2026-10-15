{{-- AIS CTA banner: homepage signature globe + consultation actions. --}}
@props(['title' => 'Let&rsquo;s Build Your AI-Powered Future', 'text' => 'Get expert advice, a custom solution and ongoing support — all in one place.'])
<section class="relative w-full overflow-hidden border-t border-white/5" aria-labelledby="ais-cta-heading" style="background:linear-gradient(180deg,#03101f,#020916);">
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-2 gap-8 items-center">
        <div class="reveal">
            <p class="ais-eyebrow">Ready to get started?</p>
            <h2 id="ais-cta-heading" class="ais-h2 mt-3 text-2xl sm:text-3xl">{!! $title !!}</h2>
            <p class="ais-sub mt-3 text-[13px]">{{ $text }}</p>
            <div class="mt-6 flex flex-col sm:flex-row flex-wrap gap-3">
                <a href="{{ route('get-quote') }}" class="ais-btn-primary">Get a Free Consultation
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="{{ route('contact') }}" class="ais-btn-ghost">Talk to an Expert</a>
            </div>
        </div>
        <x-ais-globe static :chips="[
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z\'/>', 'Cybersecurity', 'tr'],
            ['<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17.5 19a4.5 4.5 0 100-9 6 6 0 00-11.5 1.5A4 4 0 006.5 19h11z\'/>', 'Cloud', 'mr'],
        ]" />
    </div>
</section>
