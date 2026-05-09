<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxInput;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('configures default type to text', function () {
    expect(FluxInput::make('name')->getType())->toBe('text');
});

it('switches type via DSL', function () {
    expect(FluxInput::make('email')->email()->getType())->toBe('email');
    expect(FluxInput::make('pwd')->password()->getType())->toBe('password');
    expect(FluxInput::make('phone')->tel()->getType())->toBe('tel');
});

it('exposes Flux helper toggles', function () {
    $field = FluxInput::make('email')
        ->fluxIcon('envelope')
        ->fluxIconTrailing('check')
        ->fluxClearable()
        ->fluxCopyable()
        ->fluxKbd('cmd+k')
        ->fluxSize('sm')
        ->fluxVariant('filled');

    expect($field->getFluxIcon())->toBe('envelope');
    expect($field->getFluxIconTrailing())->toBe('check');
    expect($field->isFluxClearable())->toBeTrue();
    expect($field->isFluxCopyable())->toBeTrue();
    expect($field->getFluxKbd())->toBe('cmd+k');
    expect($field->getFluxSize())->toBe('sm');
    expect($field->getFluxVariant())->toBe('filled');
});

it('renders flux:input markup with state path', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxInput::make('email')->fluxIcon('envelope'),
    ];

    $component = Livewire::test(TestForm::class);

    $html = $component->html();

    expect($html)->toContain('wire:model="data.email"');
});

it('binds state through Filament form lifecycle', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxInput::make('email'),
    ];

    Livewire::test(TestForm::class)
        ->set('data.email', 'jeff@example.com')
        ->assertSet('data.email', 'jeff@example.com');
});

it('validates required + email rule', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxInput::make('email')->email()->required(),
    ];

    Livewire::test(TestForm::class)
        ->set('data.email', 'invalid')
        ->call('save')
        ->assertHasErrors(['data.email' => 'email']);
});

it('inherits TextInput methods (autocomplete, mask, length, revealable)', function () {
    $field = FluxInput::make('password')
        ->password()
        ->revealable()
        ->autocomplete('current-password')
        ->minLength(8)
        ->maxLength(64);

    expect($field->isPasswordRevealable())->toBeTrue();
    expect($field->getAutocomplete())->toBe('current-password');
    expect($field->getMinLength())->toBe(8);
    expect($field->getMaxLength())->toBe(64);
});

it('renders viewable attribute when revealable() is set', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxInput::make('password')->password()->revealable(),
    ];

    $html = Livewire::test(TestForm::class)->html();

    expect($html)->toContain('viewable');
});
