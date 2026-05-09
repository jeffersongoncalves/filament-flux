@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'label' => $getLabel(),
        'disabled' => $isDisabled() ? 'true' : null,
        'required' => $isRequired() ? 'true' : null,
        'invalid' => $errors->has($statePath) ? 'true' : null,
    ], fn ($v) => $v !== null));
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field" :hasInlineLabel="false" :hasLabel="false">
    <x-flux::checkbox :attributes="$bag" />
</x-dynamic-component>
