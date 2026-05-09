@php
    $state = $entry->getState();
    $record = $entry->getRecord();

    $iconName = $entry->getFluxIcon();

    if ($iconName === null && filled($state) && is_string($state)) {
        $iconName = $state;
    }

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'icon' => $iconName,
        'variant' => $entry->getFluxIconVariant(),
        'class' => $entry->getFluxColor($state, $record)
            ? 'text-' . $entry->getFluxColor($state, $record) . '-500 dark:text-' . $entry->getFluxColor($state, $record) . '-400'
            : null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-dynamic-component :component="$entry->getEntryWrapperView()" :entry="$entry">
    @if ($iconName)
        <x-flux::icon :attributes="$bag" />
    @endif
</x-dynamic-component>
