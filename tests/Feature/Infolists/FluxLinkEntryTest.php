<?php

use Jeffersongoncalves\FilamentFlux\Infolists\Components\FluxLinkEntry;

it('uses the link entry view', function () {
    expect(FluxLinkEntry::make('homepage')->getView())
        ->toBe('filament-flux::components.infolist.link');
});

it('exposes link DSL on entries', function () {
    $entry = FluxLinkEntry::make('homepage')
        ->href('https://example.com')
        ->external()
        ->fluxVariant('subtle');

    expect($entry->getHref())->toBe('https://example.com');
    expect($entry->isExternal())->toBeTrue();
    expect($entry->getFluxVariant())->toBe('subtle');
});
