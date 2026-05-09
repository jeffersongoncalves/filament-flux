@props([
    'alias' => null,
    'icon' => null,
    'size' => null,
])

@php
    if (! is_string($icon)) {
        // Closures, BackedEnums (Heroicon::Sun) and HtmlString instances
        // — fall back to Filament's generator so the icon resolves correctly.
        echo \Filament\Support\generate_icon_html($icon, $alias, $attributes, $size);
        return;
    }

    $bag = $attributes->merge(['icon' => $icon], escape: false);
@endphp

<x-flux::icon :attributes="$bag" />
