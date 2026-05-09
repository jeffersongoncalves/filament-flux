@php
    $state = $entry->getState();
    $record = $entry->getRecord();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'size' => $entry->getFluxSize(),
        'color' => $entry->getFluxColor($state, $record),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-dynamic-component :component="$entry->getEntryWrapperView()" :entry="$entry">
    @if (blank($state))
        <span class="fi-fl-text-placeholder">{{ $entry->getPlaceholder() }}</span>
    @else
        <x-flux::text :attributes="$bag">{{ $state }}</x-flux::text>
    @endif
</x-dynamic-component>
