@props(['eyebrow' => null, 'title', 'copy' => null, 'align' => 'left'])

<div {{ $attributes->class(['section-heading', 'section-heading--center' => $align === 'center']) }}>
    @if ($eyebrow)
        <span class="eyebrow">{{ $eyebrow }}</span>
    @endif
    <h2>{{ $title }}</h2>
    @if ($copy)
        <p>{{ $copy }}</p>
    @endif
</div>
