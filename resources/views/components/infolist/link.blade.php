@php
    $state = $entry->getState();
    $record = $entry->getRecord();

    $href = $entry->getHref($state, $record);
    if ($href === null && filled($state) && is_string($state)) {
        $href = $state;
    }

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $href,
        'external' => $entry->isExternal() ? 'true' : null,
        'variant' => $entry->getFluxVariant(),
        'target' => $entry->isExternal() ? '_blank' : null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-dynamic-component :component="$entry->getEntryWrapperView()" :entry="$entry">
    @if (filled($state))
        <x-flux::link :attributes="$bag">{{ $state }}</x-flux::link>
    @else
        <span class="fi-fl-link-placeholder">{{ $entry->getPlaceholder() }}</span>
    @endif
</x-dynamic-component>
