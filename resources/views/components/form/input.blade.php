@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'type' => $getType(),
        'placeholder' => $getPlaceholder(),
        'autocomplete' => $getAutocomplete(),
        'autocapitalize' => $getAutocapitalize(),
        'autofocus' => $isAutofocused() ? 'autofocus' : null,
        'inputmode' => $getInputMode(),
        'step' => $getStep(),
        'min' => $getMinValue(),
        'max' => $getMaxValue(),
        'minlength' => $getMinLength(),
        'maxlength' => $getMaxLength(),
        'icon' => $getFluxIcon(),
        'icon:trailing' => $getFluxIconTrailing(),
        'icon:variant' => $getFluxIconVariant(),
        'size' => $getFluxSize(),
        'variant' => $getFluxVariant(),
        'kbd' => $getFluxKbd(),
        'clearable' => $isFluxClearable() ? 'true' : null,
        'copyable' => $isFluxCopyable() ? 'true' : null,
        'viewable' => $isPasswordRevealable() ? 'true' : null,
        'disabled' => $isDisabled() ? 'true' : null,
        'readonly' => $isReadOnly() ? 'true' : null,
        'required' => $isRequired() ? 'true' : null,
        'invalid' => $errors->has($statePath) ? 'true' : null,
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($getExtraInputAttributes(), escape: false);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-flux::input :attributes="$bag" />
</x-dynamic-component>
