<?php

use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;
use Jeffersongoncalves\FilamentFlux\Support\AssetInjector;

it('renders compiled @fluxAppearance markup when enabled', function () {
    $plugin = FilamentFluxPlugin::make()->injectAppearance();

    expect(AssetInjector::appearance($plugin))
        ->toContain('Flux.applyAppearance')
        ->toContain('flux.appearance');
});

it('bridges Filament theme-changed event into Flux appearance', function () {
    $plugin = FilamentFluxPlugin::make()->injectAppearance();
    $html = AssetInjector::appearance($plugin);

    expect($html)
        ->toContain("localStorage.getItem('theme')")
        ->toContain("addEventListener('theme-changed'")
        ->toContain("addEventListener('storage'")
        ->toContain('Flux.applyAppearance');
});

it('omits @fluxAppearance markup when disabled', function () {
    $plugin = FilamentFluxPlugin::make()->injectAppearance(false);

    expect(AssetInjector::appearance($plugin))->not->toContain('Flux.applyAppearance');
});

it('renders compiled @fluxScripts markup when enabled', function () {
    $plugin = FilamentFluxPlugin::make()->injectScripts();

    expect(AssetInjector::scripts($plugin))->toContain('flux.min.js');
});

it('omits @fluxScripts markup when disabled', function () {
    $plugin = FilamentFluxPlugin::make()->injectScripts(false);

    expect(AssetInjector::scripts($plugin))->not->toContain('flux.min.js');
});

it('opens and closes scope wrapper when class is set', function () {
    $plugin = FilamentFluxPlugin::make()->scopeClass('test-scope');

    expect(AssetInjector::scopeOpen($plugin))->toBe('<div class="test-scope">');
    expect(AssetInjector::scopeClose($plugin))->toBe('</div>');
});

it('emits empty wrapper when scope class is null', function () {
    $plugin = FilamentFluxPlugin::make()->scopeClass(null);

    expect(AssetInjector::scopeOpen($plugin))->toBe('');
    expect(AssetInjector::scopeClose($plugin))->toBe('');
});

it('escapes HTML special characters in scope class', function () {
    $plugin = FilamentFluxPlugin::make()->scopeClass('test"scope');

    expect(AssetInjector::scopeOpen($plugin))->toContain('test&quot;scope');
});
