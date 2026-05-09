@php
    $state = $getState();
    $record = $getRecord();

    $iconName = $column->getFluxIcon();

    if ($iconName === null && filled($state) && is_string($state)) {
        $iconName = $state;
    }

    $color = $column->getFluxColor($state, $record);

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'icon' => $iconName,
        'variant' => $column->getFluxIconVariant(),
        'class' => $color ? 'text-' . $color . '-500 dark:text-' . $color . '-400' : null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

@if ($iconName)
    <x-flux::icon :attributes="$bag" />
@endif
