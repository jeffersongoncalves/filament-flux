@php
    $state = $getState();
    $record = $getRecord();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'color' => $column->getFluxColor($state, $record),
        'icon' => $column->getFluxIcon(),
        'variant' => $column->getFluxBadgeVariant(),
        'size' => $column->getFluxSize(),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

@if (blank($state))
    <span class="fi-fl-badge-column-placeholder">—</span>
@else
    <x-flux::badge :attributes="$bag">{{ $state }}</x-flux::badge>
@endif
