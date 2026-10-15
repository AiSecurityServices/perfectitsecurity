{{-- Reference process: left intro + right 4 steps with icon dots, numbers, connectors. Props: eyebrow, title, sub, steps [[icon, num, t, d]] --}}
@props(['eyebrow' => 'Our Process', 'title' => '', 'sub' => '', 'steps' => [], 'id' => 'process-heading'])
<section class="relative w-full border-t border-white/5" aria-labelledby="{{ $id }}" style="background:#020916;">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 py-12 lg:py-16 grid lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-4 reveal">
            <p class="ais-eyebrow">{{ $eyebrow }}</p>
            <h2 id="{{ $id }}" class="ais-h2 mt-3 text-2xl sm:text-[1.7rem] leading-tight">{{ $title }}</h2>
            <p class="ais-sub mt-4 text-[13px] leading-relaxed">{{ $sub }}</p>
        </div>
        <ol class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach($steps as $i => $s)
            <li class="relative reveal text-center sm:text-left" style="transition-delay:{{ $i * 80 }}ms;">
                @if($i < count($steps) - 1)<span class="hidden xl:block absolute top-[23px] left-[52px] right-[-24px] h-px" style="background:linear-gradient(90deg, rgba(0,207,255,.5), rgba(0,207,255,.08));" aria-hidden="true"></span>@endif
                <span class="ais-step-dot relative" aria-hidden="true"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $s[0] !!}</svg></span>
                <p class="mt-3 text-[11px] font-bold tracking-widest" style="color:#00CFFF;">{{ $s[1] }}</p>
                <h3 class="text-white text-[14px] font-bold">{{ $s[2] }}</h3>
                <p class="mt-1 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $s[3] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
