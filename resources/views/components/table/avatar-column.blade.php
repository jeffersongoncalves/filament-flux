@php
    $state = $getState();
    $record = $getRecord();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'src' => $column->getFluxSrc($state, $record) ?? (is_string($state) ? $state : null),
        'name' => $column->getFluxName($state, $record),
        'color' => $column->getFluxColor($state, $record),
        'size' => $column->getFluxSize(),
        'badge' => $column->getFluxBadge($state, $record),
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::avatar :attributes="$bag" />
