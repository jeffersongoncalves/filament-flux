<?php

use Jeffersongoncalves\FilamentFlux\Actions\FluxAction;

it('exposes a stable name', function () {
    expect(FluxAction::make('publish')->getName())->toBe('publish');
});

it('uses our custom view by default', function () {
    expect(FluxAction::make('publish')->getView())
        ->toBe('filament-flux::components.action');
});

it('exposes Flux DSL setters', function () {
    $action = FluxAction::make('publish')
        ->fluxVariant('primary')
        ->fluxIcon('rocket-launch')
        ->fluxIconTrailing('arrow-right')
        ->fluxKbd('cmd+enter')
        ->fluxLoading()
        ->fluxSize('sm')
        ->fluxTooltip('Publish now', 'right');

    expect($action->getFluxVariant())->toBe('primary');
    expect($action->getFluxIcon())->toBe('rocket-launch');
    expect($action->getFluxIconTrailing())->toBe('arrow-right');
    expect($action->getFluxKbd())->toBe('cmd+enter');
    expect($action->getFluxLoading())->toBeTrue();
    expect($action->getFluxSize())->toBe('sm');
    expect($action->getFluxTooltip())->toBe('Publish now');
    expect($action->getFluxTooltipPosition())->toBe('right');
});

it('accepts a wire:target string for fluxLoading', function () {
    $action = FluxAction::make('save')->fluxLoading('save');

    expect($action->getFluxLoading())->toBe('save');
});

it('renders <flux:button> with wire:click handler', function () {
    $html = FluxAction::make('publish')
        ->fluxVariant('primary')
        ->fluxIcon('rocket-launch')
        ->action(fn () => null)
        ->toHtml();

    expect($html)
        ->toContain('mountAction')
        ->toContain('Publish')
        ->toContain('data-flux-icon');
});
