{{-- Reference hero: eyebrow + big title + lede + 2x2 feature chips + 2 CTAs + homepage signature globe with floating glass chips.
     Props: eyebrow, title (html), lede (html), features [[icon, html]], actions slot, chips [[iconSvg, label, pos?]], breadcrumbs slot. --}}
@props(['eyebrow' => '', 'title' => '', 'lede' => '', 'features' => [], 'chips' => []])
<section class="ais-hero relative w-full overflow-hidden" aria-labelledby="ais-hero-heading">
    <div class="ais-hero-glow" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-14 sm:pt-18 lg:pt-22 pb-10 lg:pb-14 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            @if(isset($breadcrumbs))<div class="mb-5">{{ $breadcrumbs }}</div>@endif
            @if($eyebrow)<p class="ais-eyebrow reveal">{{ $eyebrow }}</p>@endif
            <h1 id="ais-hero-heading" class="mt-4 font-extrabold tracking-tight leading-[1.05] text-4xl sm:text-5xl reveal" style="font-family:Sora,Inter,system-ui,sans-serif;"><span class="text-white">{!! $title !!}</span></h1>
            @if($lede)<p class="mt-5 text-[14px] sm:text-[15px] leading-relaxed max-w-xl reveal delay-100" style="color:#B6C8DD;">{!! $lede !!}</p>@endif
            @if(count($features))
            <ul class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 max-w-xl reveal delay-200" aria-label="Highlights">
                @foreach($features as $f)
                <li class="ais-chip"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $f[0] !!}</svg><span>{!! $f[1] !!}</span></li>
                @endforeach
            </ul>
            @endif
            @if(isset($actions))<div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3 reveal delay-200">{{ $actions }}</div>@endif
            {{ $slot }}
        </div>
        <x-ais-globe :chips="$chips" />
    </div>
</section>
