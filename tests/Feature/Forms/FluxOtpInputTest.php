<?php

use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxOtpInput;
use Jeffersongoncalves\FilamentFlux\Tests\Fixtures\TestForm;
use Livewire\Livewire;

beforeEach(function () {
    TestForm::$fieldsCallback = null;
});

it('uses the OTP view', function () {
    expect(FluxOtpInput::make('code')->getView())
        ->toBe('filament-flux::components.form.otp');
});

it('inherits length default of 6 from OneTimeCodeInput', function () {
    expect(FluxOtpInput::make('code')->getLength())->toBe(6);
});

it('accepts custom length and private flag', function () {
    $field = FluxOtpInput::make('code')->length(4)->private();

    expect($field->getLength())->toBe(4);
    expect($field->isPrivate())->toBeTrue();
});

it('binds state through Filament form', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxOtpInput::make('code')->length(6),
    ];

    Livewire::test(TestForm::class)
        ->set('data.code', '123456')
        ->assertSet('data.code', '123456');
});
