<?php

use Filament\Panel;
use Illuminate\Support\Facades\View;
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

it('returns no active navigation overrides by default', function () {
    expect(FilamentFluxPlugin::make()->getActiveNavigationOverrides())->toBe([]);
});

it('useFluxNavigation(true) enables both sidebar and topbar', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxNavigation();

    expect($plugin->getActiveNavigationOverrides())->toMatchArray(['sidebar', 'topbar']);
});

it('useFluxNavigation(false) clears the overrides', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxNavigation()->useFluxNavigation(false);

    expect($plugin->getActiveNavigationOverrides())->toBe([]);
});

it('useFluxNavigation(array) merges over the defaults', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxNavigation(['topbar' => false]);

    expect($plugin->getActiveNavigationOverrides())->toBe(['sidebar']);
});

it('prepends the override path for sidebar when enabled', function () {
    FilamentFluxPlugin::make()
        ->useFluxNavigation(['sidebar' => true, 'topbar' => false])
        ->register(Panel::make()->id('nav-test')->path('nav-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();

    expect($hints)->toHaveKey('filament-panels');
    expect($hints['filament-panels'][0])->toContain(DIRECTORY_SEPARATOR.'panels-overrides'.DIRECTORY_SEPARATOR.'sidebar');
});

it('prepends both override paths when both areas are enabled', function () {
    FilamentFluxPlugin::make()
        ->useFluxNavigation()
        ->register(Panel::make()->id('nav-test')->path('nav-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-panels'] ?? []);

    expect($joined)
        ->toContain('panels-overrides'.DIRECTORY_SEPARATOR.'sidebar')
        ->toContain('panels-overrides'.DIRECTORY_SEPARATOR.'topbar');
});

it('does not register override paths when navigation is off', function () {
    FilamentFluxPlugin::make()
        ->register(Panel::make()->id('nav-test')->path('nav-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-panels'] ?? []);

    expect($joined)->not->toContain('panels-overrides');
});
