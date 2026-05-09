@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'placeholder' => $getPlaceholder(),
        'rows' => $getRows() ?? 4,
        'resize' => $getResize(),
        'autocomplete' => $getAutocomplete(),
        'autofocus' => $isAutofocused() ? 'autofocus' : null,
        'minlength' => $getMinLength(),
        'maxlength' => $getMaxLength(),
        'disabled' => $isDisabled() ? 'true' : null,
        'readonly' => $isReadOnly() ? 'true' : null,
        'required' => $isRequired() ? 'true' : null,
        'invalid' => $errors->has($statePath) ? 'true' : null,
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($getExtraInputAttributes(), escape: false);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-flux::textarea :attributes="$bag" />
</x-dynamic-component>
