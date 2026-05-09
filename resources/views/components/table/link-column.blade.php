@php
    $state = $getState();
    $record = $getRecord();

    $href = $column->getHref($state, $record);
    if ($href === null && filled($state) && is_string($state)) {
        $href = $state;
    }

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $href,
        'external' => $column->isExternal() ? 'true' : null,
        'variant' => $column->getFluxVariant(),
        'target' => $column->isExternal() ? '_blank' : null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

@if (filled($state))
    <x-flux::link :attributes="$bag">{{ $state }}</x-flux::link>
@else
    <span class="fi-fl-link-column-placeholder">—</span>
@endif
