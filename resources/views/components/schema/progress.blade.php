@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'value' => $schemaComponent->getValue(),
        'color' => $schemaComponent->getFluxColor(),
        'size' => $schemaComponent->getFluxSize(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::progress :attributes="$bag" />
