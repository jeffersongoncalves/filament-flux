@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'level' => $schemaComponent->getLevel(),
        'size' => $schemaComponent->getSize(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::heading :attributes="$bag">{{ $schemaComponent->getContent() }}</x-flux::heading>
