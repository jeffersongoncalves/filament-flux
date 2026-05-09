@php
    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'name' => $name,
        'variant' => $variant,
        'position' => $position,
    ], fn ($v) => $v !== null && $v !== ''));

    if (! $dismissible) {
        $bag = $bag->merge(['dismissible' => 'false']);
    }

    if (! $closable) {
        $bag = $bag->merge(['closable' => 'false']);
    }
@endphp

@if ($trigger)
    <x-flux::modal.trigger :name="$name">{!! $trigger !!}</x-flux::modal.trigger>
@endif

<x-flux::modal :attributes="$bag">
    {!! $content !!}
</x-flux::modal>
