@props(['title'])

<div {{ $attributes->class(['section-heading']) }}>
    <h3>{{ $title }}</h3>
    @isset($subtitle)
        <p>{{ $subtitle }}</p>
    @endisset
</div>
