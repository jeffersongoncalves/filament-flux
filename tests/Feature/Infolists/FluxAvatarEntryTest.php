<?php

use Jeffersongoncalves\FilamentFlux\Infolists\Components\FluxAvatarEntry;

it('uses the avatar entry view', function () {
    expect(FluxAvatarEntry::make('avatar')->getView())
        ->toBe('filament-flux::components.infolist.avatar');
});

it('exposes Flux avatar DSL', function () {
    $entry = FluxAvatarEntry::make('avatar')
        ->fluxSrc('https://cdn/me.png')
        ->fluxName('Jefferson Gonçalves')
        ->fluxColor('blue')
        ->fluxSize('lg')
        ->fluxBadge('green');

    expect($entry->getFluxSrc())->toBe('https://cdn/me.png');
    expect($entry->getFluxName())->toBe('Jefferson Gonçalves');
    expect($entry->getFluxColor())->toBe('blue');
    expect($entry->getFluxSize())->toBe('lg');
    expect($entry->getFluxBadge())->toBe('green');
});

it('accepts closure resolvers receiving state and record', function () {
    $entry = FluxAvatarEntry::make('avatar')
        ->fluxName(fn (mixed $state, mixed $record) => $record?->name ?? 'fallback');

    $record = (object) ['name' => 'Ada'];

    expect($entry->getFluxName(null, $record))->toBe('Ada');
});
