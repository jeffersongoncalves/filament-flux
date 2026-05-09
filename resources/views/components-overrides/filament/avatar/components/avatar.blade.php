@props([
    'circular' => true,
    'size' => 'md',
])

@php
    $fluxSize = match ($size) {
        'sm' => 'xs',
        'md' => 'sm',
        'lg' => 'lg',
        default => is_string($size) ? $size : 'sm',
    };

    $src = $attributes->get('src');
    $alt = $attributes->get('alt');

    $bag = (new \Illuminate\View\ComponentAttributeBag(array_filter([
        'size' => $fluxSize,
        'src' => $src,
        'alt' => $alt,
        'circle' => $circular ? 'true' : null,
    ], fn ($v) => $v !== null && $v !== '')))
        ->merge($attributes->except(['src', 'alt'])->getAttributes(), escape: false);
@endphp

<x-flux::avatar :attributes="$bag" />
