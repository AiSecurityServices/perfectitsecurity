{{-- Homepage signature globe, reusable on every page. Props: chips [[iconSvg, label, pos]], static (force CSS fallback art, no WebGL), class. --}}
@props(['chips' => [], 'static' => false, 'class' => ''])
@php $positions = ['tl', 'tr', 'ml', 'mr', 'br', 'bc']; @endphp
<div class="hero-globe-stage reveal delay-100 {{ $class }}" @if($static) data-globe="fallback" @else data-globe="live" @endif aria-hidden="true">
    <div class="hero-globe-fallback"></div>
    @if(!$static)<canvas class="hero-globe-canvas" tabindex="-1"></canvas>@endif
    @foreach($chips as $i => $c)
    <div class="hero-float hero-float--{{ $c[2] ?? $positions[$i % 6] }}"><strong><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $c[0] !!}</svg>{{ $c[1] }}</strong></div>
    @endforeach
</div>
