@props([
    'availableHeight' => null,
    'availableWidth' => null,
    'flip' => true,
    'maxHeight' => null,
    'offset' => 8,
    'placement' => null,
    'shift' => false,
    'size' => false,
    'sizePadding' => 16,
    'teleport' => false,
    'trigger' => null,
    'width' => null,
])

@php
    $align = match (true) {
        is_string($placement) && str_starts_with($placement, 'bottom-end') => 'end',
        is_string($placement) && str_starts_with($placement, 'bottom-start') => 'start',
        is_string($placement) && str_starts_with($placement, 'top-end') => 'end',
        is_string($placement) && str_starts_with($placement, 'top-start') => 'start',
        default => null,
    };

    $position = match (true) {
        is_string($placement) && str_starts_with($placement, 'top') => 'top',
        is_string($placement) && str_starts_with($placement, 'left') => 'left',
        is_string($placement) && str_starts_with($placement, 'right') => 'right',
        default => 'bottom',
    };

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'position' => $position,
        'align' => $align,
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($attributes->getAttributes(), escape: false);
@endphp

<x-flux::dropdown :attributes="$bag">
    @if ($trigger)
        {{ $trigger }}
    @endif

    @if (! \Filament\Support\is_slot_empty($slot))
        <x-flux::menu>
            {{ $slot }}
        </x-flux::menu>
    @endif
</x-flux::dropdown>
