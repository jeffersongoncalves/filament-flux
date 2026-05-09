@php
    $statePath = $getStatePath();
    $bindKey = $applyStateBindingModifiers('wire:model');

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        $bindKey => $statePath,
        'disabled' => $isDisabled() ? 'true' : null,
        'required' => $isRequired() ? 'true' : null,
        'invalid' => $errors->has($statePath) ? 'true' : null,
    ], fn ($v) => $v !== null));
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    @if ($isInline())
        <x-slot name="labelPrefix">
            <x-flux::checkbox :attributes="$bag" />
        </x-slot>
    @else
        <x-flux::checkbox :attributes="$bag" />
    @endif
</x-dynamic-component>
