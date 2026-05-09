@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'heading' => $schemaComponent->getHeading(),
        'icon' => $schemaComponent->getFluxIcon(),
        'icon:variant' => $schemaComponent->getFluxIconVariant(),
        'variant' => $schemaComponent->getVariant(),
        'color' => $schemaComponent->getFluxColor(),
        'inline' => $schemaComponent->isInline() ? 'true' : null,
    ], fn ($v) => $v !== null && $v !== ''));

    $text = $schemaComponent->getText();
@endphp

<x-flux::callout :attributes="$bag">
    @if ($text)
        <x-flux::callout.text>{{ $text }}</x-flux::callout.text>
    @endif
</x-flux::callout>
