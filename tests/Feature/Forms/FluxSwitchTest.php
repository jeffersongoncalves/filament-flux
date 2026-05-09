<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxSwitch;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('defaults align to right', function () {
    expect(FluxSwitch::make('notifications')->getFluxAlign())->toBe('right');
});

it('switches align via DSL', function () {
    expect(FluxSwitch::make('x')->fluxAlign('left')->getFluxAlign())->toBe('left');
});

it('binds boolean state', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxSwitch::make('notifications'),
    ];

    Livewire::test(TestForm::class)
        ->set('data.notifications', true)
        ->assertSet('data.notifications', true);
});

it('renders <flux:switch> with wire:model', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxSwitch::make('notifications'),
    ];

    expect(Livewire::test(TestForm::class)->html())
        ->toContain('wire:model="data.notifications"');
});
