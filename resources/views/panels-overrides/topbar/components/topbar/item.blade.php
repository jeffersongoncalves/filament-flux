@props([
    'active' => false,
    'activeIcon' => null,
    'badge' => null,
    'badgeColor' => null,
    'badgeTooltip' => null,
    'icon' => null,
    'shouldOpenUrlInNewTab' => false,
    'url' => null,
])

@php
    $resolvedIcon = ($active && $activeIcon) ? $activeIcon : $icon;
    $iconString = is_string($resolvedIcon) ? $resolvedIcon : null;

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $url,
        'target' => ($url && $shouldOpenUrlInNewTab) ? '_blank' : null,
        'wire:current' => $active ? 'true' : null,
        'icon' => $iconString,
        'badge' => $badge,
        'badge-color' => $badgeColor,
        'badge:tooltip' => $badgeTooltip,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::navbar.item :attributes="$bag">
    {{ $slot }}
</x-flux::navbar.item>
