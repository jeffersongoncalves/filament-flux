<?php

use Filament\Panel;
use Illuminate\Support\Facades\View;
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

it('returns no active component overrides by default', function () {
    expect(FilamentFluxPlugin::make()->getActiveComponentOverrides())->toBe([]);
});

it('useFluxComponents(true) enables every registered slug', function () {
    $plugin = FilamentFluxPlugin::make()->useFluxComponents();

    expect($plugin->getActiveComponentOverrides())->toMatchArray([
        'badge',
        'avatar',
        'icon',
        'iconButton',
        'link',
        'breadcrumbs',
        'callout',
        'card',
        'fieldset',
        'section',
        'dropdown',
        'dropdownHeader',
        'modalHeading',
        'modalDescription',
        'schemaText',
        'statsCard',
        'notifications',
    ]);
});

it('schemaText slug uses the filament-schemas namespace', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['schemaText' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-schemas'] ?? []);

    expect($joined)->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament-schemas'.DIRECTORY_SEPARATOR.'schemaText');
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

it('prepends paths for H3 typography/header slugs', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents([
            'dropdownHeader' => true,
            'modalHeading' => true,
            'modalDescription' => true,
        ])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament'] ?? []);

    expect($joined)
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'dropdown-header')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'modal-heading')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'modal-description');
});

it('statsCard slug uses the filament-widgets namespace', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['statsCard' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-widgets'] ?? []);

    expect($joined)->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament-widgets'.DIRECTORY_SEPARATOR.'statsCard');
});

it('statsCard override resolves the stats-overview-widget.stat view', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['statsCard' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $resolved = View::getFinder()->find('filament-widgets::stats-overview-widget.stat');

    expect($resolved)->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament-widgets'.DIRECTORY_SEPARATOR.'statsCard');
});

it('notifications slug uses the filament-notifications namespace', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['notifications' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament-notifications'] ?? []);

    expect($joined)->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament-notifications'.DIRECTORY_SEPARATOR.'notifications');
});

it('notifications override resolves the filament-notifications::notifications view', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents(['notifications' => true])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $resolved = View::getFinder()->find('filament-notifications::notifications');

    expect($resolved)->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament-notifications'.DIRECTORY_SEPARATOR.'notifications');
});

it('prepends paths for H2 wrapper slugs', function () {
    FilamentFluxPlugin::make()
        ->useFluxComponents([
            'callout' => true,
            'card' => true,
            'fieldset' => true,
            'section' => true,
            'dropdown' => true,
        ])
        ->register(Panel::make()->id('comp-test')->path('comp-test'));

    $factory = View::getFinder();
    $hints = $factory->getHints();
    $joined = implode("\n", $hints['filament'] ?? []);

    expect($joined)
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'callout')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'card')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'fieldset')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'section')
        ->toContain('components-overrides'.DIRECTORY_SEPARATOR.'filament'.DIRECTORY_SEPARATOR.'dropdown');
});
