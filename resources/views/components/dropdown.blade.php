@php
    /** @var \Jeffersongoncalves\FilamentFlux\Actions\FluxDropdown $group */
    $label = $group->getLabel();
    $icon = $group->getFluxIcon() ?? $group->getIcon();
    $size = $group->getFluxSize() ?? $group->getSize()?->value;
    $variant = $group->getFluxVariant() ?? 'outline';
@endphp

<x-flux::dropdown>
    <x-flux::button
        :variant="$variant"
        :size="$size"
        :icon="$icon"
        icon:trailing="chevron-down"
    >
        {{ $label }}
    </x-flux::button>

    <x-flux::menu>
        @foreach ($group->getActions() as $childAction)
            @php
                $childIcon = method_exists($childAction, 'getFluxIcon') ? $childAction->getFluxIcon() : null;
                $childIcon ??= $childAction->getIcon();
                $childUrl = $childAction->getUrl();
                $childClick = $childAction->getLivewireClickHandler();
            @endphp

            <x-flux::menu.item
                :icon="$childIcon"
                :href="$childUrl"
                :wire:click="$childClick"
                :disabled="$childAction->isDisabled()"
            >
                {{ $childAction->getLabel() }}
            </x-flux::menu.item>
        @endforeach
    </x-flux::menu>
</x-flux::dropdown>
