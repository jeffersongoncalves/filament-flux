<?php

use Jeffersongoncalves\FilamentFlux\Infolists\Components\FluxBadgeEntry;

it('uses the badge entry view', function () {
    expect(FluxBadgeEntry::make('status')->getView())
        ->toBe('filament-flux::components.infolist.badge');
});

it('exposes Flux DSL setters', function () {
    $entry = FluxBadgeEntry::make('status')
        ->fluxColor('lime')
        ->fluxIcon('check-circle')
        ->fluxBadgeVariant('pill')
        ->fluxSize('sm');

    expect($entry->getFluxColor('any'))->toBe('lime');
    expect($entry->getFluxIcon())->toBe('check-circle');
    expect($entry->getFluxBadgeVariant())->toBe('pill');
    expect($entry->getFluxSize())->toBe('sm');
});

it('resolves color from a state map at render time', function () {
    $entry = FluxBadgeEntry::make('status')->fluxColor([
        'draft' => 'zinc',
        'published' => 'lime',
    ]);

    expect($entry->getFluxColor('draft'))->toBe('zinc');
    expect($entry->getFluxColor('published'))->toBe('lime');
});
