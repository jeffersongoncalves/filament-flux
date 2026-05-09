<?php

use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

it('exposes a stable plugin id', function () {
    expect(FilamentFluxPlugin::make()->getId())->toBe('filament-flux');
});

it('defaults all toggles to enabled', function () {
    $plugin = FilamentFluxPlugin::make();

    expect($plugin->shouldInjectAppearance())->toBeTrue();
    expect($plugin->shouldInjectScripts())->toBeTrue();
    expect($plugin->getScopeClass())->toBe('filament-flux-scope');
});

it('flips toggles via fluent setters', function () {
    $plugin = FilamentFluxPlugin::make()
        ->injectAppearance(false)
        ->injectScripts(false)
        ->scopeClass(null);

    expect($plugin->shouldInjectAppearance())->toBeFalse();
    expect($plugin->shouldInjectScripts())->toBeFalse();
    expect($plugin->getScopeClass())->toBeNull();
});

it('accepts a custom scope class', function () {
    $plugin = FilamentFluxPlugin::make()->scopeClass('my-scope');

    expect($plugin->getScopeClass())->toBe('my-scope');
});
