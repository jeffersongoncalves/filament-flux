@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');
    $type = $getType();
    $isPassword = $type === 'password';
    $viewable = $isPassword && method_exists($field, 'isPasswordRevealable') && $field->isPasswordRevealable();

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'type' => $type,
        'placeholder' => $getPlaceholder(),
        'autocomplete' => $getAutocomplete(),
        'autocapitalize' => $getAutocapitalize(),
        'autofocus' => $isAutofocused() ? 'autofocus' : null,
        'inputmode' => $getInputMode(),
        'step' => $getStep(),
        'min' => ($isPassword || $type === 'text' || $type === 'email' || $type === 'tel' || $type === 'url') ? null : $getMinValue(),
        'max' => ($isPassword || $type === 'text' || $type === 'email' || $type === 'tel' || $type === 'url') ? null : $getMaxValue(),
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
        'viewable' => $viewable ? 'true' : null,
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
