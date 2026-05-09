<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxSelect;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('exposes options DSL', function () {
    $field = FluxSelect::make('status')->options([
        'draft' => 'Rascunho',
        'published' => 'Publicado',
    ]);

    expect($field->getOptions())->toMatchArray([
        'draft' => 'Rascunho',
        'published' => 'Publicado',
    ]);
});

it('defaults Flux variant to default', function () {
    expect(FluxSelect::make('x')->getFluxVariant())->toBe('default');
});

it('renders <flux:select.option> for each option', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxSelect::make('status')->options(['draft' => 'Rascunho']),
    ];

    expect(Livewire::test(TestForm::class)->html())
        ->toContain('wire:model="data.status"')
        ->toContain('value="draft"')
        ->toContain('Rascunho');
});

it('binds and validates required', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxSelect::make('status')
            ->options(['draft' => 'Rascunho', 'published' => 'Publicado'])
            ->required(),
    ];

    Livewire::test(TestForm::class)
        ->set('data.status', null)
        ->call('save')
        ->assertHasErrors(['data.status' => 'required']);
});
