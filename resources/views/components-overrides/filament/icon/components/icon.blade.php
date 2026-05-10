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

    $iconName = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::name($icon);
    $iconVariant = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::variant($icon);

    if ($iconName === null) {
        // Closures, HtmlString instances, or other non-resolvable inputs —
        // fall back to Filament's generator. Cast through toHtml() because
        // BladeUI\Icons\Svg lacks __toString().
        $generated = \Filament\Support\generate_icon_html($icon, $alias, $attributes, $size);
        echo $generated?->toHtml() ?? '';
        return;
    }

    $bag = $attributes->merge(array_filter([
        'icon' => $iconName,
        'variant' => $iconVariant,
    ], fn ($v) => $v !== null), escape: false);
@endphp

<x-flux::icon :attributes="$bag" />
