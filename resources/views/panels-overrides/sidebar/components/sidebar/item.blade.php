@props([
    'active' => false,
    'activeChildItems' => false,
    'activeIcon' => null,
    'badge' => null,
    'badgeColor' => null,
    'badgeTooltip' => null,
    'childItems' => [],
    'first' => false,
    'grouped' => false,
    'icon' => null,
    'last' => false,
    'shouldOpenUrlInNewTab' => false,
    'sidebarCollapsible' => true,
    'subGrouped' => false,
    'subNavigation' => false,
    'url',
])

@php
    $resolvedIcon = ($active && $activeIcon) ? $activeIcon : $icon;
    $iconString = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::name($resolvedIcon);
    $iconVariant = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::variant($resolvedIcon);

    // Non-heroicon sets (Font Awesome, Tabler, etc.) — pre-render as HtmlString.
    // <flux:navlist.item> accepts an Htmlable for $icon and renders it inline.
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

<x-flux::navlist.item :attributes="$bag">
    {{ $slot }}
</x-flux::navlist.item>

@if (($active || $activeChildItems) && $childItems)
    @foreach ($childItems as $childItem)
        @php
            $isChildItemChildItemsActive = $childItem->isChildItemsActive();
            $isChildActive = (! $isChildItemChildItemsActive) && $childItem->isActive();
            $childItemActiveIcon = $childItem->getActiveIcon();
            $childItemBadge = $childItem->getBadge();
            $childItemBadgeColor = $childItem->getBadgeColor($childItemBadge);
            $childItemBadgeTooltip = $childItem->getBadgeTooltip($childItemBadge);
            $childItemIcon = $childItem->getIcon();
            $shouldChildItemOpenUrlInNewTab = $childItem->shouldOpenUrlInNewTab();
            $childItemUrl = $childItem->getUrl();
        @endphp

        <x-filament-panels::sidebar.item
            :active="$isChildActive"
            :active-child-items="$isChildItemChildItemsActive"
            :active-icon="$childItemActiveIcon"
            :badge="$childItemBadge"
            :badge-color="$childItemBadgeColor"
            :badge-tooltip="$childItemBadgeTooltip"
            :first="$loop->first"
            grouped
            :icon="$childItemIcon"
            :last="$loop->last"
            :should-open-url-in-new-tab="$shouldChildItemOpenUrlInNewTab"
            sub-grouped
            :sub-navigation="$subNavigation"
            :url="$childItemUrl"
        >
            {{ $childItem->getLabel() }}
        </x-filament-panels::sidebar.item>
    @endforeach
@endif
