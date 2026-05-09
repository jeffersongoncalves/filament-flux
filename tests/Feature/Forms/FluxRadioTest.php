<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxRadio;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxRadioGroup;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('FluxRadio inherits options DSL from Filament Radio', function () {
    $field = FluxRadio::make('plan')->options(['free' => 'Free', 'pro' => 'Pro']);

    expect($field->getOptions())->toMatchArray(['free' => 'Free', 'pro' => 'Pro']);
});

it('FluxRadioGroup binds state and renders wire:model', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxRadioGroup::make('plan')->options([
            'free' => 'Free',
            'pro' => 'Pro',
        ]),
    ];

    $component = Livewire::test(TestForm::class)
        ->set('data.plan', 'pro')
        ->assertSet('data.plan', 'pro');

    expect($component->html())->toContain('wire:model="data.plan"');
});

it('FluxRadioGroup variant defaults to default', function () {
    expect(FluxRadioGroup::make('plan')->getFluxVariant())->toBe('default');
});

it('FluxRadioGroup variant accepts segmented', function () {
    expect(FluxRadioGroup::make('plan')->fluxVariant('segmented')->getFluxVariant())
        ->toBe('segmented');
});

it('FluxRadioGroup is an alias of FluxRadio', function () {
    expect(FluxRadioGroup::make('plan'))->toBeInstanceOf(FluxRadio::class);
});
