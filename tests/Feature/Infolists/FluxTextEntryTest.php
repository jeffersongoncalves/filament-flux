<?php

use Jeffersongoncalves\FilamentFlux\Infolists\Components\FluxTextEntry;

it('uses the text entry view', function () {
    expect(FluxTextEntry::make('description')->getView())
        ->toBe('filament-flux::components.infolist.text');
});

it('exposes size and color DSL', function () {
    $entry = FluxTextEntry::make('description')
        ->fluxSize('lg')
        ->fluxColor('zinc');

    expect($entry->getFluxSize())->toBe('lg');
    expect($entry->getFluxColor())->toBe('zinc');
});
