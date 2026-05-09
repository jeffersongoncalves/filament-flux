<?php

use Jeffersongoncalves\FilamentFlux\Tables\Columns\FluxBadgeColumn;

it('uses the badge column view', function () {
    expect(FluxBadgeColumn::make('status')->getView())
        ->toBe('filament-flux::components.table.badge-column');
});

it('exposes badge DSL setters', function () {
    $column = FluxBadgeColumn::make('status')
        ->fluxColor([
            'draft' => 'zinc',
            'published' => 'lime',
        ])
        ->fluxIcon(fn (mixed $state) => $state === 'published' ? 'check-circle' : null)
        ->fluxBadgeVariant('pill')
        ->fluxSize('sm');

    expect($column->getFluxColor('draft'))->toBe('zinc');
    expect($column->getFluxColor('published'))->toBe('lime');
    expect($column->getFluxBadgeVariant())->toBe('pill');
    expect($column->getFluxSize())->toBe('sm');
});
