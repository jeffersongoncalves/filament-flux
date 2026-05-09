@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'variant' => $getFluxVariant(),
        'size' => $getFluxSize(),
        'placeholder' => $getPlaceholder(),
        'disabled' => $isDisabled() ? 'true' : null,
        'required' => $isRequired() ? 'true' : null,
        'invalid' => $errors->has($statePath) ? 'true' : null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-flux::select :attributes="$bag">
        @foreach ($getOptions() as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </x-flux::select>
</x-dynamic-component>
