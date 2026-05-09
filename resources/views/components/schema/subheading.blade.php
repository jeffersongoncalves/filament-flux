@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'size' => $schemaComponent->getSize(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::subheading :attributes="$bag">{{ $schemaComponent->getContent() }}</x-flux::subheading>
