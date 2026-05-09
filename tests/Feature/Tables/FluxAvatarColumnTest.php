<?php

use Jeffersongoncalves\FilamentFlux\Tables\Columns\FluxAvatarColumn;

it('uses the avatar column view', function () {
    expect(FluxAvatarColumn::make('user.avatar')->getView())
        ->toBe('filament-flux::components.table.avatar-column');
});

it('exposes avatar DSL with closures receiving record', function () {
    $column = FluxAvatarColumn::make('user.avatar')
        ->fluxName(fn (mixed $state, mixed $record) => $record?->name)
        ->fluxColor(fn (mixed $state, mixed $record) => $record?->is_admin ? 'blue' : 'zinc')
        ->fluxSize('sm')
        ->fluxBadge('green');

    $admin = (object) ['name' => 'Ada', 'is_admin' => true];
    $user = (object) ['name' => 'Joe', 'is_admin' => false];

    expect($column->getFluxName(null, $admin))->toBe('Ada');
    expect($column->getFluxColor(null, $admin))->toBe('blue');
    expect($column->getFluxColor(null, $user))->toBe('zinc');
    expect($column->getFluxSize())->toBe('sm');
    expect($column->getFluxBadge())->toBe('green');
});
