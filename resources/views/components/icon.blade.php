@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'icon' => $name,
        'variant' => $variant,
        'class' => $class,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::icon :attributes="$bag" />
