@php
    $url = $action->getUrl();
    $tooltip = $action->getFluxTooltip() ?? $action->getTooltip();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $url,
        'wire:click' => ! $url ? $action->getLivewireClickHandler() : null,
        'x-on:click' => $action->getAlpineClickHandler(),
        'target' => ($url && $action->shouldOpenUrlInNewTab()) ? '_blank' : null,
        'external' => $action->isExternal() ? 'true' : null,
        'variant' => $action->getFluxVariant(),
        'accent' => $action->isAccent() ? null : 'false',
    ], fn ($v) => $v !== null && $v !== ''));

    if ($action->isStrong()) {
        $bag = $bag->merge(['strong' => 'true']);
    }
@endphp

<x-flux::link :attributes="$bag">
    {{ $action->getLabel() }}
</x-flux::link>

@if (filled($tooltip))
    <x-flux::tooltip :content="$tooltip" :position="$action->getFluxTooltipPosition()" />
@endif
