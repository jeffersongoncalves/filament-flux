<?php

use Jeffersongoncalves\FilamentFlux\Tables\Columns\FluxIconColumn;

it('uses the icon column view', function () {
    expect(FluxIconColumn::make('priority')->getView())
        ->toBe('filament-flux::components.table.icon-column');
});

it('resolves icon name from a closure', function () {
    $column = FluxIconColumn::make('priority')
        ->fluxIcon(fn () => 'fire')
        ->fluxIconVariant('solid')
        ->fluxColor('red');

    expect($column->getFluxIcon())->toBe('fire');
    expect($column->getFluxIconVariant())->toBe('solid');
    expect($column->getFluxColor())->toBe('red');
});
