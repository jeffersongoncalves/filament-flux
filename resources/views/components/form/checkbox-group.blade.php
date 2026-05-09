@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'variant' => $getFluxVariant(),
        'invalid' => $errors->has($statePath) ? 'true' : null,
    ], fn ($v) => $v !== null));
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-flux::checkbox.group :attributes="$bag">
        @foreach ($getOptions() as $value => $label)
            <x-flux::checkbox :value="$value" :label="$label" />
        @endforeach
    </x-flux::checkbox.group>
</x-dynamic-component>
