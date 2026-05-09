@props([
    'alpineDeferredBadgeData' => null,
    'alpineDeferredBadgeLoading' => null,
    'badge' => null,
    'badgeColor' => 'primary',
    'badgeTooltip' => null,
    'color' => 'gray',
    'disabled' => false,
    'href' => null,
    'icon' => null,
    'iconAlias' => null,
    'iconColor' => null,
    'iconSize' => null,
    'image' => null,
    'keyBindings' => null,
    'loadingIndicator' => true,
    'spaMode' => null,
    'tag' => 'button',
    'target' => null,
    'tooltip' => null,
])

@php
    if ($tag === 'form') {
        // Form-tag dropdown items wrap a hidden form (CSRF, POST). Flux's
        // menu items don't model that, so emit Filament's native markup.
        echo view('filament::components.dropdown.list.item.original', get_defined_vars())->render();
        return;
    }

    $iconString = is_string($icon) ? $icon : null;
    $kbd = is_array($keyBindings) ? implode('+', $keyBindings) : null;

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $tag === 'a' ? $href : null,
        'target' => $target,
        'icon' => $iconString,
        'kbd' => $kbd,
        'disabled' => $disabled ? 'true' : null,
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($attributes->getAttributes(), escape: false);
@endphp

<x-flux::menu.item :attributes="$bag">
    {{ $slot }}
</x-flux::menu.item>
