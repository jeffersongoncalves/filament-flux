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
    $iconString = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::name($resolvedIcon);
    $iconVariant = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::variant($resolvedIcon);

    $iconHtml = null;
    if ($iconString === null && filled($resolvedIcon)) {
        $generated = \Filament\Support\generate_icon_html($resolvedIcon);
        $iconHtml = $generated ? new \Illuminate\Support\HtmlString($generated->toHtml()) : null;
    }

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $url,
        'target' => ($url && $shouldOpenUrlInNewTab) ? '_blank' : null,
        'wire:current' => $active ? 'true' : null,
        'icon' => $iconString ?? $iconHtml,
        'icon:variant' => $iconVariant,
        'badge' => $badge,
        'badge-color' => $badgeColor,
        'badge:tooltip' => $badgeTooltip,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::navbar.item :attributes="$bag">
    {{ $slot }}
</x-flux::navbar.item>
