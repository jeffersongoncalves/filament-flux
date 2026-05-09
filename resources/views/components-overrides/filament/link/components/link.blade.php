@php
    use Filament\Support\Enums\Size;
@endphp

@props([
    'badge' => null,
    'badgeColor' => 'primary',
    'badgeSize' => Size::ExtraSmall,
    'color' => 'primary',
    'disabled' => false,
    'href' => null,
    'icon' => null,
    'iconAlias' => null,
    'iconPosition' => null,
    'iconSize' => null,
    'labelSrOnly' => false,
    'size' => Size::Medium,
    'spaMode' => null,
    'tag' => 'a',
    'target' => null,
    'tooltip' => null,
    'type' => 'button',
    'weight' => null,
    'form' => null,
    'formId' => null,
    'keyBindings' => null,
    'loadingIndicator' => true,
])

@php
    $fluxVariant = match ($color) {
        'gray' => 'subtle',
        default => null,
    };

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'href' => $tag === 'a' ? $href : null,
        'target' => $target,
        'variant' => $fluxVariant,
    ], fn ($v) => $v !== null && $v !== ''));

    $bag = $bag->merge($attributes->getAttributes(), escape: false);
@endphp

<x-flux::link :attributes="$bag">
    @unless ($labelSrOnly)
        {{ $slot }}
    @endunless
</x-flux::link>
