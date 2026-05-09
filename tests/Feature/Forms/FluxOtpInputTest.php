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

it('defaults digits to 6', function () {
    expect(FluxOtpInput::make('code')->getDigits())->toBe(6);
});

it('accepts custom digits and private flag', function () {
    $field = FluxOtpInput::make('code')->digits(4)->private();

    expect($field->getDigits())->toBe(4);
    expect($field->isPrivate())->toBeTrue();
});

it('binds state through Filament form', function () {
    TestForm::$fieldsCallback = fn () => [
        FluxOtpInput::make('code')->digits(6),
    ];

    Livewire::test(TestForm::class)
        ->set('data.code', '123456')
        ->assertSet('data.code', '123456');
});
