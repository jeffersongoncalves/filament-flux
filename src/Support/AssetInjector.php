<?php

namespace Jeffersongoncalves\FilamentFlux\Support;

use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

class AssetInjector
{
    public static function appearance(FilamentFluxPlugin $plugin): string
    {
        return view('filament-flux::render-hooks.flux-appearance', [
            'injectAppearance' => $plugin->shouldInjectAppearance(),
        ])->render();
    }

    public static function scripts(FilamentFluxPlugin $plugin): string
    {
        return view('filament-flux::render-hooks.flux-scripts', [
            'injectScripts' => $plugin->shouldInjectScripts(),
        ])->render();
    }

    public static function scopeOpen(FilamentFluxPlugin $plugin): string
    {
        $class = $plugin->getScopeClass();

        if ($class === null) {
            return '';
        }

        return '<div class="'.e($class).'">';
    }

    public static function scopeClose(FilamentFluxPlugin $plugin): string
    {
        if ($plugin->getScopeClass() === null) {
            return '';
        }

        return '</div>';
    }
}
