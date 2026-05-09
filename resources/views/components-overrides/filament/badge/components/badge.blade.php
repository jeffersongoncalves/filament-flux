@php
    use Filament\Support\Enums\Size;

    $color = $color ?? 'primary';
    $size = $size ?? Size::Medium;

    $fluxColor = match ($color) {
        'primary' => 'blue',
        'success' => 'lime',
        'warning' => 'amber',
        'danger' => 'red',
        'info' => 'cyan',
        'gray' => 'zinc',
        default => is_string($color) ? $color : 'zinc',
    };

    $fluxSize = match (true) {
        $size === Size::ExtraSmall || $size === 'xs' => 'xs',
        $size === Size::Small || $size === 'sm' => 'sm',
        default => null,
    };

    $bag = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'color' => $fluxColor,
        'size' => $fluxSize,
        'icon' => $icon ?? null,
        'href' => ($tag ?? null) === 'a' ? ($href ?? null) : null,
        'target' => $target ?? null,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

<x-flux::badge :attributes="$bag">
    {{ $slot }}
</x-flux::badge>
