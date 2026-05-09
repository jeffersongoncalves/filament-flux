<?php

use Jeffersongoncalves\FilamentFlux\Tables\Columns\FluxLinkColumn;

it('uses the link column view', function () {
    expect(FluxLinkColumn::make('homepage')->getView())
        ->toBe('filament-flux::components.table.link-column');
});

it('exposes href and external setters', function () {
    $column = FluxLinkColumn::make('homepage')
        ->href(fn ($state, $record) => $record?->homepage_url)
        ->external()
        ->fluxVariant('ghost');

    $record = (object) ['homepage_url' => 'https://example.com'];

    expect($column->getHref(null, $record))->toBe('https://example.com');
    expect($column->isExternal())->toBeTrue();
    expect($column->getFluxVariant())->toBe('ghost');
});
