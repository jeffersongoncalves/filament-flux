@php
    use Filament\Support\Enums\IconSize;
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

    if (filled($iconSize) && ! ($iconSize instanceof IconSize)) {
        $iconSize = IconSize::tryFrom((string) $iconSize) ?? null;
    }

    $iconSize ??= match ($size) {
        Size::ExtraSmall => IconSize::Small,
        Size::Large, Size::ExtraLarge => IconSize::Large,
        default => null,
    };

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

    $iconString = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::name($icon);
    $iconVariant = \Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer::variant($icon);
@endphp

@if ($iconString === null)
    {{-- Non-string icons (Heroicon enum, Closure, HtmlString) — wrap the
         original Filament-generated icon HTML inside a flux:button. --}}
    @php
        $iconHtmlable = \Filament\Support\generate_icon_html($icon, $iconAlias, new \Illuminate\View\ComponentAttributeBag, $iconSize);
        $iconHtml = $iconHtmlable?->toHtml() ?? '';

        $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
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

    <x-flux::button :attributes="$bag">
        {!! $iconHtml !!}
    </x-flux::button>
@else
    @php
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
@endif
