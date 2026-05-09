<?php

use Jeffersongoncalves\FilamentFlux\Components\FluxModal;

it('builds with a name', function () {
    $m = FluxModal::make('confirm-delete')
        ->variant('floating')
        ->position('right')
        ->dismissible(false)
        ->closable(false);

    expect($m)->toBeInstanceOf(FluxModal::class);
});

it('exposes open and close event payloads', function () {
    $open = FluxModal::openEvent('confirm-delete');
    $close = FluxModal::closeEvent('confirm-delete');

    expect($open)->toMatchArray([
        'name' => 'modal-show',
        'action' => 'show',
        'modal' => 'confirm-delete',
    ]);

    expect($close)->toMatchArray([
        'name' => 'modal-close',
        'action' => 'close',
        'modal' => 'confirm-delete',
    ]);
});
