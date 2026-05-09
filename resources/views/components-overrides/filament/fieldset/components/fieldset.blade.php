@props([
    'contained' => true,
    'label' => null,
    'labelHidden' => false,
    'required' => false,
])

<x-flux::fieldset {{ $attributes }}>
    @if (filled($label) && ! $labelHidden)
        <x-flux::legend>
            {{ $label }}@if ($required)<sup class="fi-fieldset-label-required-mark">*</sup>@endif
        </x-flux::legend>
    @endif

    {{ $slot }}
</x-flux::fieldset>
