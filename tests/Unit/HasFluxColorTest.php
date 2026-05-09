<?php

use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;

beforeEach(function () {
    $this->subject = new class
    {
        use HasFluxColor;

        public function evaluate(mixed $value, array $named = [], array $typed = []): mixed
        {
            if ($value instanceof Closure) {
                return $value(...array_values($named));
            }

            return $value;
        }
    };
});

it('returns null when color is not configured', function () {
    expect($this->subject->getFluxColor())->toBeNull();
});

it('returns the configured static color', function () {
    $this->subject->fluxColor('lime');

    expect($this->subject->getFluxColor('whatever'))->toBe('lime');
});

it('resolves color from a state to color array map', function () {
    $this->subject->fluxColor([
        'draft' => 'zinc',
        'published' => 'lime',
        'archived' => 'red',
    ]);

    expect($this->subject->getFluxColor('draft'))->toBe('zinc');
    expect($this->subject->getFluxColor('published'))->toBe('lime');
    expect($this->subject->getFluxColor('unknown'))->toBeNull();
});

it('resolves color from a closure receiving state and record', function () {
    $this->subject->fluxColor(fn (mixed $state, mixed $record) => $state === 'admin' ? 'blue' : 'zinc');

    expect($this->subject->getFluxColor('admin', null))->toBe('blue');
    expect($this->subject->getFluxColor('user', null))->toBe('zinc');
});

it('returns null array map lookup for non-scalar state', function () {
    $this->subject->fluxColor(['draft' => 'zinc']);

    expect($this->subject->getFluxColor(null))->toBeNull();
    expect($this->subject->getFluxColor(['array']))->toBeNull();
});
