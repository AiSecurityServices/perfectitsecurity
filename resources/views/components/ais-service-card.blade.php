{{-- Reference service card: visual header, title, desc, Learn More link. --}}
@props(['href' => '#', 'title' => '', 'desc' => '', 'art' => '', 'icon' => '', 'label' => null])
<article class="ais-card group overflow-hidden reveal flex flex-col">
    <div class="h-28 relative flex items-center justify-center" style="background:{{ $art }};" aria-hidden="true">
        <svg class="w-11 h-11" fill="none" viewBox="0 0 24 24" stroke="#BFE9FF" stroke-width="1.4">{!! $icon !!}</svg>
        <div class="absolute inset-0" style="background:linear-gradient(180deg, transparent 30%, rgba(2,9,22,.85));"></div>
    </div>
    <div class="p-4 flex flex-col flex-1">
        <h3 class="text-[13.5px] font-bold text-white leading-snug">{{ $title }}</h3>
        <p class="mt-1.5 text-[12px] leading-relaxed flex-1" style="color:#B6C8DD;">{{ $desc }}</p>
        <a href="{{ $href }}" class="ais-link mt-3" @if($label) aria-label="{{ $label }}" @endif>Learn More
            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
    </div>
</article>
