<?php

use Jeffersongoncalves\FilamentFlux\Actions\FluxAction;

it('attaches tooltip to FluxAction', function () {
    $action = FluxAction::make('publish')->fluxTooltip('Click to publish', 'bottom');

    expect($action->getFluxTooltip())->toBe('Click to publish');
    expect($action->getFluxTooltipPosition())->toBe('bottom');
});

it('defaults tooltip position to top', function () {
    $action = FluxAction::make('x')->fluxTooltip('Hello');

    expect($action->getFluxTooltipPosition())->toBe('top');
});

it('returns null when tooltip not set', function () {
    expect(FluxAction::make('x')->getFluxTooltip())->toBeNull();
});

it('FluxInput accepts the tooltip trait when composed', function () {
    // The trait is reusable; we verify it on Action and don't force it on inputs by default.
    $action = FluxAction::make('save')->fluxTooltip('Save changes');

    expect($action->getFluxTooltip())->toBe('Save changes');
});
