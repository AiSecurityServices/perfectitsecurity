{{-- Reference section head: cyan eyebrow, bold white title, muted sub. --}}
@props(['eyebrow' => '', 'title' => '', 'sub' => '', 'id' => null, 'center' => false])
<div class="reveal @if($center) text-center mx-auto max-w-3xl @endif">
    @if($eyebrow)<p class="ais-eyebrow">{{ $eyebrow }}</p>@endif
    <h2 @if($id) id="{{ $id }}" @endif class="ais-h2 mt-3 text-2xl sm:text-3xl">{{ $title }}</h2>
    @if($sub)<p class="ais-sub mt-3 text-[13px] max-w-3xl @if($center) mx-auto @endif">{{ $sub }}</p>@endif
</div>
