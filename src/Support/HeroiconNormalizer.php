<?php

namespace Jeffersongoncalves\FilamentFlux\Support;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Strip Filament/Blade Icons prefixes from heroicon names so the result
 * matches the Flux icon library's component naming. Returns null when the
 * icon cannot be safely resolved by `<flux:icon>` — callers should then
 * fall back to `\Filament\Support\generate_icon_html()` so blade-icons
 * sets (Font Awesome, Tabler, Lucide, etc.) keep working.
 */
class HeroiconNormalizer
{
    /**
     * Known prefixes from non-heroicon blade-icons sets. Icons starting
     * with any of these MUST go through `generate_icon_html()` because
     * Flux only ships heroicons.
     */
    public const NON_HEROICON_PREFIXES = [
        'fontawesome-',
        'fab-',
        'far-',
        'fas-',
        'tabler-',
        'lucide-',
        'phosphor-',
        'gmdi-',
        'mdi-',
        'octicon-',
        'eos-icons-',
        'bi-',
        'bxl-',
        'bxs-',
        'bx-',
        'feather-',
        'simple-icons-',
        'css-gg-',
    ];

    /**
     * Returns a bare heroicon name suitable for `<flux:icon>` or `null`
     * when the input belongs to another icon set (in which case the
     * caller should use `\Filament\Support\generate_icon_html()`).
     */
    public static function name(mixed $icon): ?string
    {
        if ($icon instanceof BackedEnum) {
            $value = $icon->value;
        } elseif (is_string($icon)) {
            $value = $icon;
        } else {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        foreach (self::NON_HEROICON_PREFIXES as $prefix) {
            if (str_starts_with($value, $prefix)) {
                return null;
            }
        }

        // Filament/Blade Icons heroicon variants — strip the prefix.
        $stripped = (string) preg_replace('/^heroicon-(?:o|s|c|m|mini|micro|outline|solid)-/', '', $value);

        // Bare `heroicon-NAME` (no variant letter).
        $stripped = (string) preg_replace('/^heroicon-/', '', $stripped);

        // Filament v5 Heroicon enum convention without the `heroicon-`
        // prefix: `o-bars-3`, `s-cog`, `m-trash`, `c-bell`, `mini-...`,
        // `micro-...`, `outline-...`, `solid-...`.
        $stripped = (string) preg_replace('/^(?:o|s|c|m|mini|micro|outline|solid)-/', '', $stripped);

        return $stripped !== '' ? $stripped : null;
    }

    /**
     * Resolve the Flux variant from a Filament-style icon name.
     */
    public static function variant(mixed $icon): ?string
    {
        if ($icon instanceof BackedEnum) {
            $icon = $icon->value;
        }

        if (! is_string($icon)) {
            return null;
        }

        return match (true) {
            str_starts_with($icon, 'heroicon-s-'), str_starts_with($icon, 'heroicon-solid-'), str_starts_with($icon, 's-'), str_starts_with($icon, 'solid-') => 'solid',
            str_starts_with($icon, 'heroicon-mini-'), str_starts_with($icon, 'heroicon-m-'), str_starts_with($icon, 'mini-'), str_starts_with($icon, 'm-') => 'mini',
            str_starts_with($icon, 'heroicon-micro-'), str_starts_with($icon, 'heroicon-c-'), str_starts_with($icon, 'micro-'), str_starts_with($icon, 'c-') => 'micro',
            str_starts_with($icon, 'heroicon-o-'), str_starts_with($icon, 'heroicon-outline-'), str_starts_with($icon, 'o-'), str_starts_with($icon, 'outline-') => 'outline',
            default => null,
        };
    }

    /**
     * Whether the given icon can be normalized to a Flux-compatible string.
     */
    public static function isResolvable(mixed $icon): bool
    {
        if ($icon instanceof Htmlable) {
            return false;
        }

        return self::name($icon) !== null;
    }
}
