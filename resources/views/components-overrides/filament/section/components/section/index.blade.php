@php
    use Filament\Support\Enums\IconSize;
    use function Filament\Support\is_slot_empty;
@endphp

@props([
    'afterHeader' => null,
    'aside' => false,
    'collapsed' => false,
    'collapseId' => null,
    'collapsible' => false,
    'compact' => false,
    'contained' => true,
    'contentBefore' => false,
    'description' => null,
    'divided' => false,
    'footer' => null,
    'hasContentEl' => true,
    'heading' => null,
    'headingTag' => 'h2',
    'icon' => null,
    'iconColor' => 'gray',
    'iconSize' => null,
    'persistCollapsed' => false,
    'secondary' => false,
])

@php
    $hasDescription = filled((string) $description);
    $hasHeading = filled($heading);
    $hasIcon = filled($icon);
    $hasFooter = ! is_slot_empty($footer);
    $hasHeader = $hasIcon || $hasHeading || $hasDescription || $collapsible || (! is_slot_empty($afterHeader));

    $iconString = is_string($icon) ? $icon : null;
@endphp

<x-flux::card
    {{
        $attributes->class([
            'fi-section',
            'fi-section-not-contained' => ! $contained,
            'fi-compact' => $compact,
            'fi-secondary' => $secondary,
        ])
    }}
    x-data="{
        isCollapsed: @if ($persistCollapsed) $persist(@js($collapsed)).as(`section-${@js($collapseId) ?? $el.id}-isCollapsed`) @else @js($collapsed) @endif,
    }"
    @if ($collapsible)
        x-on:collapse-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = true"
        x-on:expand="isCollapsed = false"
        x-on:expand-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = false"
        x-on:open-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = false"
        x-on:toggle-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = ! isCollapsed"
        x-bind:class="isCollapsed && 'fi-collapsed'"
    @endif
>
    @if ($hasHeader)
        <header
            @if ($collapsible)
                x-on:click="isCollapsed = ! isCollapsed"
            @endif
            class="fi-section-header"
        >
            @if ($hasIcon && $iconString)
                <x-flux::icon :icon="$iconString" class="fi-section-header-icon" />
            @elseif ($hasIcon)
                {{ \Filament\Support\generate_icon_html($icon, size: $iconSize ?? IconSize::Large) }}
            @endif

            @if ($hasHeading || $hasDescription)
                <div class="fi-section-header-text-ctn">
                    @if ($hasHeading)
                        <x-flux::heading size="lg">{{ $heading }}</x-flux::heading>
                    @endif

                    @if ($hasDescription)
                        <x-flux::text>{{ $description }}</x-flux::text>
                    @endif
                </div>
            @endif

            @if (! is_slot_empty($afterHeader))
                <div class="fi-section-header-after-ctn">
                    {{ $afterHeader }}
                </div>
            @endif

            @if ($collapsible)
                <x-flux::icon
                    icon="chevron-down"
                    class="fi-section-collapse-icon"
                    x-bind:class="isCollapsed && 'fi-rotate-180'"
                />
            @endif
        </header>
    @endif

    @if ($hasContentEl)
        <div
            @if ($collapsible)
                x-show="! isCollapsed"
                x-collapse
            @endif
            @class([
                'fi-section-content',
                'fi-divided' => $divided,
            ])
        >
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif

    @if ($hasFooter)
        <div class="fi-section-footer">
            {{ $footer }}
        </div>
    @endif
</x-flux::card>
