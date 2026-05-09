<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxTextarea;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('exposes resize and rows DSL', function () {
    $field = FluxTextarea::make('bio')
        ->rows(8)
        ->resize('vertical');

    expect($field->getRows())->toBe(8);
    expect($field->getResize())->toBe('vertical');
});

it('binds state through Filament form', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxTextarea::make('bio'),
    ];

    Livewire::test(TestForm::class)
        ->set('data.bio', 'lorem ipsum')
        ->assertSet('data.bio', 'lorem ipsum');
});

it('renders wire:model bound to state path', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxTextarea::make('bio'),
    ];

    expect(Livewire::test(TestForm::class)->html())
        ->toContain('wire:model="data.bio"');
});

it('validates required rule', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxTextarea::make('bio')->required(),
    ];

    Livewire::test(TestForm::class)
        ->set('data.bio', '')
        ->call('save')
        ->assertHasErrors(['data.bio' => 'required']);
});
