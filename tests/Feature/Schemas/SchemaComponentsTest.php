<?php

use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxCallout;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxCard;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxFieldset;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxHeading;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxProgress;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxSeparator;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxSkeleton;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxSpacer;
use Jeffersongoncalves\FilamentFlux\Schemas\Components\FluxSubheading;

it('FluxHeading exposes content/level/size', function () {
    $h = FluxHeading::make('Title')->level('1')->size('xl');

    expect($h->getContent())->toBe('Title');
    expect($h->getLevel())->toBe('1');
    expect($h->getSize())->toBe('xl');
    expect($h->getView())->toBe('filament-flux::components.schema.heading');
});

it('FluxSubheading exposes content/size', function () {
    $h = FluxSubheading::make('Subtitle')->size('sm');

    expect($h->getContent())->toBe('Subtitle');
    expect($h->getSize())->toBe('sm');
    expect($h->getView())->toBe('filament-flux::components.schema.subheading');
});

it('FluxSeparator exposes orientation/variant/text', function () {
    $s = FluxSeparator::make()
        ->orientation('horizontal')
        ->variant('subtle')
        ->text('OR');

    expect($s->getOrientation())->toBe('horizontal');
    expect($s->getVariant())->toBe('subtle');
    expect($s->getText())->toBe('OR');
});

it('FluxSpacer renders the spacer view', function () {
    expect(FluxSpacer::make()->getView())->toBe('filament-flux::components.schema.spacer');
});

it('FluxCallout exposes heading/text/variant/inline', function () {
    $c = FluxCallout::make()
        ->heading('Heads up')
        ->text('Pay attention')
        ->variant('warning')
        ->fluxIcon('exclamation-triangle')
        ->inline();

    expect($c->getHeading())->toBe('Heads up');
    expect($c->getText())->toBe('Pay attention');
    expect($c->getVariant())->toBe('warning');
    expect($c->getFluxIcon())->toBe('exclamation-triangle');
    expect($c->isInline())->toBeTrue();
});

it('FluxFieldset stores legend', function () {
    $f = FluxFieldset::make('Personal info');

    expect($f->getLegend())->toBe('Personal info');
    expect($f->getView())->toBe('filament-flux::components.schema.fieldset');
});

it('FluxCard exposes the card view', function () {
    expect(FluxCard::make()->getView())->toBe('filament-flux::components.schema.card');
});

it('FluxSkeleton accepts a class override', function () {
    $s = FluxSkeleton::make()->class('h-10 w-full');

    expect($s->getClass())->toBe('h-10 w-full');
});

it('FluxProgress reads the value', function () {
    expect(FluxProgress::make()->value(72)->getValue())->toBe(72);
    expect(FluxProgress::make()->getValue())->toBe(0);
});
