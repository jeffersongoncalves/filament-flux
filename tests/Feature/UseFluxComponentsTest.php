<?php

use Filament\Panel;
use Illuminate\Support\Facades\View;
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

it('returns no active component overrides by default', function () {
    expect(FilamentFluxPlugin::make()->getActiveComponentOverrides())->toBe([]);
});

it('useFluxComponents(true) enables every H1 slug', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxComponents();

    expect($plugin->getActiveComponentOverrides())->toMatchArray([
        'badge',
        'avatar',
        'icon',
        'iconButton',
        'link',
        'breadcrumbs',
    ]);
});

it('useFluxComponents(false) clears active overrides', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxComponents()->useFluxComponents(false);

    expect($plugin->getActiveComponentOverrides())->toBe([]);
});

it('useFluxComponents(array) treats unspecified slugs as off', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxComponents([
        'badge' => true,
        'avatar' => true,
    ]);

    expect($plugin->getActiveComponentOverrides())->toMatchArray(['badge', 'avatar']);
    expect($plugin->getActiveComponentOverrides())->not->toContain('icon');
});

it('prepends the override path for each enabled slug', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['badge' => true, 'icon' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament'] ?? []);

    expect($joined)
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'badge')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'icon');
});

it('does not register override paths when components feature is off', function () {
    FilamentFluxPlugin::make()
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament'] ?? []);

    expect($joined)->not->toContain('components-overrides');
});

it('uses the filament namespace (not filament-panels) for the prepend', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['badge' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();

    expect($hints)->toHaveKey('filament');
});
