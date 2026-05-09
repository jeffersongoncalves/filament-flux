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
    <x-flux::radio.group :attributes="$bag">
        @foreach ($getOptions() as $value => $label)
            <x-flux::radio :value="$value" :label="$label" />
        @endforeach
    </x-flux::radio.group>
</x-dynamic-component>
