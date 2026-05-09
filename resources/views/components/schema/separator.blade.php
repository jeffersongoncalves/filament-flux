@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'orientation' => $schemaComponent->getOrientation(),
        'variant' => $schemaComponent->getVariant(),
        'text' => $schemaComponent->getText(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::separator :attributes="$bag" />
