<?php

use Jeffersongoncalves\FilamentFlux\Actions\FluxLinkAction;

it('uses the link-action view', function () {
    expect(FluxLinkAction::make('docs')->getView())
        ->toBe('filament-flux::components.link-action');
});

it('exposes link DSL', function () {
    $action = FluxLinkAction::make('docs')
        ->url('https://example.com/docs')
        ->external()
        ->accent(false)
        ->strong()
        ->fluxVariant('subtle')
        ->fluxIcon('book-open');

    expect($action->getUrl())->toBe('https://example.com/docs');
    expect($action->isExternal())->toBeTrue();
    expect($action->isAccent())->toBeFalse();
    expect($action->isStrong())->toBeTrue();
    expect($action->getFluxVariant())->toBe('subtle');
    expect($action->getFluxIcon())->toBe('book-open');
});
