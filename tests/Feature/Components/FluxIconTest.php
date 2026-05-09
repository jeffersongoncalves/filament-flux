<?php

use Jeffersongoncalves\FilamentFlux\Support\FluxIcon;

it('builds with name', function () {
    expect(FluxIcon::make('star')->getName())->toBe('star');
});

it('exposes variant DSL', function () {
    expect(FluxIcon::make('star')->fluxVariant('outline')->getVariant())->toBe('outline');
});

it('exposes class DSL', function () {
    expect(FluxIcon::make('star')->class('size-6')->getClass())->toBe('size-6');
});

it('renders <flux:icon> SVG markup', function () {
    $html = (string) FluxIcon::make('star')->fluxVariant('outline')->class('size-6');

    expect($html)
        ->toContain('<svg')
        ->toContain('size-6')
        ->toContain('data-flux-icon');
});
