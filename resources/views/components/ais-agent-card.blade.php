{{-- Reference agent card: colored icon tile top-left, arrow top-right, bold title, short desc. --}}
@props(['href' => '#', 'title' => '', 'desc' => '', 'icon' => '', 'bg' => 'rgba(22,139,255,.18)', 'fg' => '#4DA3FF', 'label' => null])
<a href="{{ $href }}" class="ais-card group block p-4 reveal" @if($label) aria-label="{{ $label }}" @endif>
    <div class="flex items-start justify-between gap-2">
        <span class="ais-icon" style="background:{{ $bg }}; color:{{ $fg }};" aria-hidden="true"><svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icon !!}</svg></span>
        <svg class="w-4 h-4 mt-1 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="#7FB7E8" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
    </div>
    <h3 class="mt-3 text-[13.5px] font-bold text-white leading-snug">{{ $title }}</h3>
    <p class="mt-1.5 text-[12px] leading-relaxed" style="color:#B6C8DD;">{{ $desc }}</p>
</a>
