{{-- Reference stats strip: single glass bar, circular icon + big value + label, divided. Props: items [[icon, value, label]] --}}
@props(['items' => []])
<section class="relative w-full" aria-label="Highlights" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pb-2">
        <div class="ais-card reveal px-6 py-5 grid grid-cols-2 lg:grid-cols-{{ count($items) >= 4 ? 4 : count($items) }} gap-6">
            @foreach($items as $i => $s)
            <div class="flex items-center gap-3.5 {{ $i > 0 ? 'lg:border-l lg:border-white/10 lg:pl-6' : '' }}">
                <span class="ais-icon !w-10 !h-10 !rounded-full" style="background:rgba(0,207,255,.12); color:#00CFFF; border-color:rgba(0,207,255,.4);" aria-hidden="true"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $s[0] !!}</svg></span>
                <span><strong class="block text-white text-[15px] font-extrabold leading-tight">{{ $s[1] }}</strong><span class="block text-[12px]" style="color:#B6C8DD;">{{ $s[2] }}</span></span>
            </div>
            @endforeach
        </div>
    </div>
</section>
