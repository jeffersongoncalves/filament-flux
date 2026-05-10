@php
    use Filament\Support\Enums\IconSize;
@endphp

@props([
    'color' => 'gray',
    'icon' => null,
    'iconSize' => null,
    'tag' => 'div',
])

@php
    if (filled($iconSize) && ! ($iconSize instanceof IconSize)) {
        $iconSize = IconSize::tryFrom((string) $iconSize) ?? null;
    }

    $iconString = is_string($icon) ? $icon : null;
@endphp

<{{ $tag }} {{ $attributes->class(['fi-dropdown-header']) }}>
    @if ($iconString)
        <x-flux::icon :icon="$iconString" />
    @elseif ($icon)
        @php
            $generated = \Filament\Support\generate_icon_html($icon, size: $iconSize);
            echo $generated?->toHtml() ?? '';
        @endphp
    @endif

    <x-flux::heading size="sm">{{ $slot }}</x-flux::heading>
</{{ $tag }}>
