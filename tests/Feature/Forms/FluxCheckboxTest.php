<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxCheckbox;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxCheckboxGroup;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('binds boolean state for FluxCheckbox', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxCheckbox::make('terms'),
    ];

    Livewire::test(TestForm::class)
        ->set('data.terms', true)
        ->assertSet('data.terms', true);
});

it('renders <flux:checkbox> with wire:model', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxCheckbox::make('terms'),
    ];

    expect(Livewire::test(TestForm::class)->html())
        ->toContain('wire:model="data.terms"');
});

it('FluxCheckboxGroup defaults variant to default', function () {
    expect(FluxCheckboxGroup::make('tags')->getFluxVariant())->toBe('default');
});

it('FluxCheckboxGroup binds array state', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxCheckboxGroup::make('tags')->options([
            '1' => 'PHP',
            '2' => 'Laravel',
        ]),
    ];

    Livewire::test(TestForm::class)
        ->set('data.tags', ['1', '2'])
        ->assertSet('data.tags', ['1', '2']);
});
