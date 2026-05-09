<?php

use Filament\Panel;
use Illuminate\Support\Facades\View;
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

it('returns no active navigation overrides by default', function () {
    expect(FilamentFluxPlugin::make()->getActiveNavigationOverrides())->toBe([]);
});

it('useFluxNavigation(true) enables sidebar+topbar but not shell', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxNavigation();

    expect($plugin->getActiveNavigationOverrides())->toMatchArray(['sidebar', 'topbar']);
    expect($plugin->getActiveNavigationOverrides())->not->toContain('shell');
});

it('useFluxNavigation([shell => true]) enables full shell override', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxNavigation(['shell' => true]);

    expect($plugin->getActiveNavigationOverrides())->toContain('shell');
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

it('prepends the shell path when the shell toggle is enabled', function () {
    FilamentFluxPlugin::make()
        ->useFluxNavigation(['shell' => true])
        ->register(Panel::make()->id('nav-test')->path('nav-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-panels'] ?? []);

    expect($joined)->toContain('panels-overrides'.DIRECTORY_SEPARATOR.'shell');
});

it('useFluxNavigation(array) keeps shell off unless explicitly enabled', function () {
    FilamentFluxPlugin::make()
        ->useFluxNavigation(['sidebar' => true])
        ->register(Panel::make()->id('nav-test')->path('nav-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-panels'] ?? []);

    expect($joined)->not->toContain('panels-overrides'.DIRECTORY_SEPARATOR.'shell');
});
