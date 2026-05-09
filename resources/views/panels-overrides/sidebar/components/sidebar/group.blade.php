@props([
    'active' => false,
    'collapsible' => true,
    'icon' => null,
    'items' => [],
    'label' => null,
    'sidebarCollapsible' => true,
    'subNavigation' => false,
])

@if (filled($label))
    <x-flux::navlist.group :heading="$label" :expandable="$collapsible">
        @foreach ($items as $item)
            @php
                $isItemChildItemsActive = $item->isChildItemsActive();
                $isItemActive = (! $isItemChildItemsActive) && $item->isActive();
                $itemActiveIcon = $item->getActiveIcon();
                $itemBadge = $item->getBadge();
                $itemBadgeColor = $item->getBadgeColor($itemBadge);
                $itemBadgeTooltip = $item->getBadgeTooltip($itemBadge);
                $itemChildItems = $item->getChildItems();
                $itemIcon = $item->getIcon();
                $shouldItemOpenUrlInNewTab = $item->shouldOpenUrlInNewTab();
                $itemUrl = $item->getUrl();
            @endphp

            <x-filament-panels::sidebar.item
                :active="$isItemActive"
                :active-child-items="$isItemChildItemsActive"
                :active-icon="$itemActiveIcon"
                :badge="$itemBadge"
                :badge-color="$itemBadgeColor"
                :badge-tooltip="$itemBadgeTooltip"
                :child-items="$itemChildItems"
                :first="$loop->first"
                :grouped="filled($label)"
                :icon="$itemIcon"
                :last="$loop->last"
                :should-open-url-in-new-tab="$shouldItemOpenUrlInNewTab"
                :sidebar-collapsible="$sidebarCollapsible"
                :sub-navigation="$subNavigation"
                :url="$itemUrl"
            >
                {{ $item->getLabel() }}
            </x-filament-panels::sidebar.item>
        @endforeach
    </x-flux::navlist.group>
@else
    @foreach ($items as $item)
        @php
            $isItemChildItemsActive = $item->isChildItemsActive();
            $isItemActive = (! $isItemChildItemsActive) && $item->isActive();
            $itemActiveIcon = $item->getActiveIcon();
            $itemBadge = $item->getBadge();
            $itemBadgeColor = $item->getBadgeColor($itemBadge);
            $itemBadgeTooltip = $item->getBadgeTooltip($itemBadge);
            $itemChildItems = $item->getChildItems();
            $itemIcon = $item->getIcon();
            $shouldItemOpenUrlInNewTab = $item->shouldOpenUrlInNewTab();
            $itemUrl = $item->getUrl();
        @endphp

        <x-filament-panels::sidebar.item
            :active="$isItemActive"
            :active-child-items="$isItemChildItemsActive"
            :active-icon="$itemActiveIcon"
            :badge="$itemBadge"
            :badge-color="$itemBadgeColor"
            :badge-tooltip="$itemBadgeTooltip"
            :child-items="$itemChildItems"
            :first="$loop->first"
            :grouped="false"
            :icon="$itemIcon"
            :last="$loop->last"
            :should-open-url-in-new-tab="$shouldItemOpenUrlInNewTab"
            :sidebar-collapsible="$sidebarCollapsible"
            :sub-navigation="$subNavigation"
            :url="$itemUrl"
        >
            {{ $item->getLabel() }}
        </x-filament-panels::sidebar.item>
    @endforeach
@endif
