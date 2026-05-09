<?php

use Jeffersongoncalves\FilamentFlux\Infolists\Components\FluxIconEntry;

it('uses the icon entry view', function () {
    expect(FluxIconEntry::make('priority')->getView())
        ->toBe('filament-flux::components.infolist.icon');
});

it('exposes icon and color DSL', function () {
    $entry = FluxIconEntry::make('priority')
        ->fluxIcon('shield-check')
        ->fluxIconVariant('solid')
        ->fluxColor('red');

    expect($entry->getFluxIcon())->toBe('shield-check');
    expect($entry->getFluxIconVariant())->toBe('solid');
    expect($entry->getFluxColor())->toBe('red');
});
