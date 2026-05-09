<x-flux::breadcrumbs>
    @foreach ($items as $item)
        @php
            $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
                'href' => $item['url'] ?? null,
                'icon' => $item['icon'] ?? null,
            ], fn ($v) => $v !== null && $v !== ''));
        @endphp

        <x-flux::breadcrumbs.item :attributes="$bag">{{ $item['label'] }}</x-flux::breadcrumbs.item>
    @endforeach
</x-flux::breadcrumbs>
