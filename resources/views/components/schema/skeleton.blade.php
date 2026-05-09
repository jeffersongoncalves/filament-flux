@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'class' => $schemaComponent->getClass(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::skeleton :attributes="$bag" />
