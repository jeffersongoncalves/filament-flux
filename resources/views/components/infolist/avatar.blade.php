@php
    $state = $entry->getState();
    $record = $entry->getRecord();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'src' => $entry->getFluxSrc($state, $record) ?? (is_string($state) ? $state : null),
        'name' => $entry->getFluxName($state, $record),
        'color' => $entry->getFluxColor($state, $record),
        'size' => $entry->getFluxSize(),
        'badge' => $entry->getFluxBadge($state, $record),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-dynamic-component :component="$entry->getEntryWrapperView()" :entry="$entry">
    <x-flux::avatar :attributes="$bag" />
</x-dynamic-component>
