<?php

use Jeffersongoncalves\FilamentFlux\Actions\FluxAction;
use Jeffersongoncalves\FilamentFlux\Actions\FluxDropdown;

it('uses our custom dropdown view', function () {
    $group = FluxDropdown::make([
        FluxAction::make('edit'),
        FluxAction::make('delete'),
    ]);

    expect($group->getView())->toBe('filament-flux::components.dropdown');
});

it('exposes Flux DSL setters on group', function () {
    $group = FluxDropdown::make([
        FluxAction::make('edit'),
    ])
        ->fluxVariant('outline')
        ->fluxIcon('ellipsis-horizontal')
        ->fluxSize('sm');

    expect($group->getFluxVariant())->toBe('outline');
    expect($group->getFluxIcon())->toBe('ellipsis-horizontal');
    expect($group->getFluxSize())->toBe('sm');
});

it('renders dropdown markup with each action label', function () {
    $html = FluxDropdown::make([
        FluxAction::make('edit'),
        FluxAction::make('delete'),
    ])->label('Actions')->toHtml();

    expect($html)
        ->toContain('Actions')
        ->toContain('ui-dropdown')
        ->toContain('ui-menu')
        ->toContain('Edit')
        ->toContain('Delete');
});
