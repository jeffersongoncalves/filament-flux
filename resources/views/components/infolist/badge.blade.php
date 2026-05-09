@php
    $state = $entry->getState();
    $record = $entry->getRecord();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'color' => $entry->getFluxColor($state, $record),
        'icon' => $entry->getFluxIcon(),
        'variant' => $entry->getFluxBadgeVariant(),
        'size' => $entry->getFluxSize(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-dynamic-component :component="$entry->getEntryWrapperView()" :entry="$entry">
    @if (blank($state))
        <span class="fi-fl-badge-placeholder">{{ $entry->getPlaceholder() }}</span>
    @else
        <x-flux::badge :attributes="$bag">{{ $state }}</x-flux::badge>
    @endif
</x-dynamic-component>
