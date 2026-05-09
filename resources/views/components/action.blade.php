@php
    $isDisabled = $action->isDisabled();
    $url = $action->getUrl();
    $shouldPostToUrl = $action->shouldPostToUrl();
    $tooltip = $action->getFluxTooltip() ?? $action->getTooltip();
    $loading = $action->getFluxLoading();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'wire:click' => $action->getLivewireClickHandler(),
        'wire:target' => $action->getLivewireTarget(),
        'x-on:click' => $action->getAlpineClickHandler(),
        'href' => ($isDisabled || $shouldPostToUrl) ? null : $url,
        'target' => ($url && $action->shouldOpenUrlInNewTab()) ? '_blank' : null,
        'variant' => $action->getFluxVariant(),
        'size' => $action->getFluxSize() ?? ($action->getSize()?->value),
        'icon' => $action->getFluxIcon() ?? $action->getIcon(),
        'icon:trailing' => $action->getFluxIconTrailing(),
        'icon:variant' => $action->getFluxIconVariant(),
        'kbd' => $action->getFluxKbd(),
        'loading' => match (true) {
            $loading === true => 'true',
            $loading === false => 'false',
            is_string($loading) => $loading,
            default => null,
        },
        'disabled' => $isDisabled ? 'true' : null,
        'type' => $action->canSubmitForm() ? 'submit' : 'button',
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($action->getExtraAttributes(), escape: false);
@endphp

<x-flux::button :attributes="$bag">
    {{ $action->getLabel() }}
</x-flux::button>

@if (filled($tooltip))
    <x-flux::tooltip :content="$tooltip" :position="$action->getFluxTooltipPosition()" />
@endif
