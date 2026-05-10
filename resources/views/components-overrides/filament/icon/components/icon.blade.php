@php
    use Filament\Support\Enums\IconSize;
@endphp

@props([
    'alias' => null,
    'icon' => null,
    'size' => null,
])

@php
    if (filled($size) && ! ($size instanceof IconSize)) {
        $size = IconSize::tryFrom((string) $size) ?? null;
    }

    if (! is_string($icon)) {
        // Closures, BackedEnums (Heroicon::Sun) and HtmlString instances
        // — fall back to Filament's generator so the icon resolves correctly.
        // The helper returns ?Htmlable; cast through toHtml() because some
        // implementations (e.g. BladeUI\Icons\Svg) lack __toString().
        $generated = \Filament\Support\generate_icon_html($icon, $alias, $attributes, $size);
        echo $generated?->toHtml() ?? '';
        return;
    }

    $bag = $attributes->merge(['icon' => $icon], escape: false);
@endphp

<x-flux::icon :attributes="$bag" />
