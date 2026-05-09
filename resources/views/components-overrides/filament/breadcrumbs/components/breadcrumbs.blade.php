@props([
    'breadcrumbs' => [],
])

<x-flux::breadcrumbs {{ $attributes }}>
    @foreach ($breadcrumbs as $url => $label)
        @if (is_int($url))
            <x-flux::breadcrumbs.item>{{ $label }}</x-flux::breadcrumbs.item>
        @else
            <x-flux::breadcrumbs.item :href="$url">{{ $label }}</x-flux::breadcrumbs.item>
        @endif
    @endforeach
</x-flux::breadcrumbs>
