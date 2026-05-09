@props([
    'color' => 'gray',
    'controls' => null,
    'description' => null,
    'footer' => null,
    'heading' => null,
    'icon' => null,
    'iconColor' => null,
    'iconSize' => null,
])

@php
    $fluxColor = match ($color) {
        'primary' => 'blue',
        'success' => 'lime',
        'warning' => 'amber',
        'danger' => 'red',
        'info' => 'cyan',
        'gray' => 'zinc',
        default => is_string($color) ? $color : 'zinc',
    };

    $fluxVariant = match ($color) {
        'success' => 'success',
        'warning' => 'warning',
        'danger' => 'danger',
        'gray' => 'secondary',
        default => null,
    };

    $iconString = is_string($icon) ? $icon : null;

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'color' => $fluxColor,
        'variant' => $fluxVariant,
        'icon' => $iconString,
        'heading' => filled($heading) ? (string) $heading : null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::callout :attributes="$bag">
    @if (filled((string) $description))
        <x-flux::callout.text>{{ $description }}</x-flux::callout.text>
    @endif

    @if (! \Filament\Support\is_slot_empty($footer))
        {{ $footer }}
    @endif

    @if (! \Filament\Support\is_slot_empty($controls))
        <x-slot name="controls">{{ $controls }}</x-slot>
    @endif

    {{ $slot }}
</x-flux::callout>
