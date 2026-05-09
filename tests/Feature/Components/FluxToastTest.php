<?php

use Jeffersongoncalves\FilamentFlux\Components\FluxToast;

it('builds variants via static helpers', function () {
    expect(FluxToast::success('Saved')->toPayload())
        ->toMatchArray(['text' => 'Saved', 'variant' => 'success']);

    expect(FluxToast::warning('Heads up')->toPayload())
        ->toMatchArray(['text' => 'Heads up', 'variant' => 'warning']);

    expect(FluxToast::danger('Boom')->toPayload())
        ->toMatchArray(['text' => 'Boom', 'variant' => 'danger']);

    expect(FluxToast::info('FYI')->toPayload())
        ->toMatchArray(['text' => 'FYI', 'variant' => 'info']);
});

it('exposes dispatch args for Livewire', function () {
    [$event, $payload] = FluxToast::success('Hello')
        ->heading('Done')
        ->duration(3000)
        ->position('top end')
        ->dispatchArgs();

    expect($event)->toBe('toast-show');
    expect($payload)->toMatchArray([
        'heading' => 'Done',
        'text' => 'Hello',
        'variant' => 'success',
        'duration' => 3000,
        'position' => 'top end',
    ]);
});

it('omits null fields from the payload', function () {
    $payload = FluxToast::make('Simple')->toPayload();

    expect($payload)->toBe(['text' => 'Simple']);
});
