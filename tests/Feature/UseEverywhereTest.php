<?php

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\OneTimeCodeInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Panel;
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxCheckbox;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxCheckboxGroup;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxInput;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxOtpInput;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxRadio;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxSelect;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxSwitch;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxTextarea;

afterEach(function () {
    // Restore the container so subsequent tests don't leak bindings.
    foreach (FilamentFluxPlugin::FIELD_BINDINGS as $binding) {
        app()->offsetUnset($binding['from']);
    }
});

it('returns no active bindings by default', function () {
    expect(FilamentFluxPlugin::make()->getActiveBindings())->toBe([]);
});

it('useEverywhere(true) registers every binding slug', function () {
    $plugin = FilamentFluxPlugin::make()->useEverywhere();

    expect($plugin->getActiveBindings())->toMatchArray([
        'input',
        'textarea',
        'select',
        'checkbox',
        'checkboxList',
        'radio',
        'toggle',
        'otp',
    ]);
});

it('useEverywhere(false) clears active bindings', function () {
    $plugin = FilamentFluxPlugin::make()->useEverywhere()->useEverywhere(false);

    expect($plugin->getActiveBindings())->toBe([]);
});

it('useEverywhere(array) merges over the defaults', function () {
    $plugin = FilamentFluxPlugin::make()->useEverywhere(['select' => false, 'otp' => false]);

    expect($plugin->getActiveBindings())
        ->toContain('input', 'textarea', 'checkbox', 'radio', 'toggle')
        ->not->toContain('select')
        ->not->toContain('otp');
});

it('binds TextInput::make() to FluxInput when active', function () {
    FilamentFluxPlugin::make()->useEverywhere()->register(Panel::make()->id('binding-test')->path('binding-test'));

    expect(TextInput::make('email'))->toBeInstanceOf(FluxInput::class);
});

it('binds every form field class to its Flux variant when useEverywhere is on', function () {
    FilamentFluxPlugin::make()->useEverywhere()->register(Panel::make()->id('binding-test')->path('binding-test'));

    expect(TextInput::make('email'))->toBeInstanceOf(FluxInput::class);
    expect(Textarea::make('bio'))->toBeInstanceOf(FluxTextarea::class);
    expect(Select::make('status'))->toBeInstanceOf(FluxSelect::class);
    expect(Checkbox::make('terms'))->toBeInstanceOf(FluxCheckbox::class);
    expect(CheckboxList::make('tags'))->toBeInstanceOf(FluxCheckboxGroup::class);
    expect(Radio::make('plan'))->toBeInstanceOf(FluxRadio::class);
    expect(Toggle::make('notifications'))->toBeInstanceOf(FluxSwitch::class);
    expect(OneTimeCodeInput::make('code'))->toBeInstanceOf(FluxOtpInput::class);
});

it('respects opt-out for individual fields', function () {
    FilamentFluxPlugin::make()
        ->useEverywhere(['select' => false])
        ->register(Panel::make()->id('binding-test')->path('binding-test'));

    expect(TextInput::make('email'))->toBeInstanceOf(FluxInput::class);
    expect(Select::make('status'))->not->toBeInstanceOf(FluxSelect::class);
    expect(Select::make('status'))->toBeInstanceOf(Select::class);
});

it('does not bind anything when useEverywhere is never called', function () {
    FilamentFluxPlugin::make()->register(Panel::make()->id('binding-test')->path('binding-test'));

    expect(TextInput::make('email'))->toBeInstanceOf(TextInput::class);
    expect(TextInput::make('email'))->not->toBeInstanceOf(FluxInput::class);
});
