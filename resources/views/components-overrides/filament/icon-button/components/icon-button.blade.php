@php
    use Filament\Support\Enums\Size;
@endphp

@props([
    'badge' => null,
    'badgeColor' => 'primary',
    'badgeSize' => Size::ExtraSmall,
    'color' => 'primary',
    'disabled' => false,
    'form' => null,
    'formId' => null,
    'href' => null,
    'icon' => null,
    'iconAlias' => null,
    'iconSize' => null,
    'keyBindings' => null,
    'label' => null,
    'loadingIndicator' => true,
    'size' => Size::Medium,
    'spaMode' => null,
    'tag' => 'button',
    'target' => null,
    'tooltip' => null,
    'type' => 'button',
])

@php
    if (! $size instanceof Size) {
        $size = filled($size) ? (Size::tryFrom($size) ?? $size) : null;
    }

    $fluxSize = match ($size) {
        Size::ExtraSmall, 'xs' => 'xs',
        Size::Small, 'sm' => 'sm',
        Size::Large, 'lg', Size::ExtraLarge => 'lg',
        default => null,
    };

    $fluxVariant = match ($color) {
        'primary' => 'primary',
        'danger' => 'danger',
        'gray' => 'subtle',
        default => 'ghost',
    };

    $iconString = is_string($icon) ? $icon : null;

    if ($iconString === null) {
        // Closures, BackedEnums (Heroicon::*), HtmlString — no clean way
        // to map; fall back to Filament's button to preserve behavior.
        $bag = $attributes->merge(['icon' => $icon, 'iconAlias' => $iconAlias, 'iconSize' => $iconSize]);
        echo \Filament\Support\generate_icon_html($icon, $iconAlias, $bag, $iconSize);
        return;
    }

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'icon' => $iconString,
        'square' => 'true',
        'size' => $fluxSize,
        'variant' => $fluxVariant,
        'href' => $tag === 'a' ? $href : null,
        'target' => $target,
        'aria-label' => $label,
        'disabled' => $disabled ? 'true' : null,
        'type' => $tag === 'button' ? $type : null,
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($attributes->getAttributes(), escape: false);
@endphp

<x-flux::button :attributes="$bag" />
