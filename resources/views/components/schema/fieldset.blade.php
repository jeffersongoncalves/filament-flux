@php
    $legend = $schemaComponent->getLegend();
@endphp

<x-flux::fieldset>
    @if ($legend)
        <x-flux::legend>{{ $legend }}</x-flux::legend>
    @endif

    {{ $schemaComponent->getChildSchema()->toHtml() }}
</x-flux::fieldset>
