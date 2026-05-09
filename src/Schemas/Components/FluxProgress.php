<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxProgress extends Component
{
    use HasFluxColor;
    use HasFluxSize;

    protected string $view = 'filament-flux::components.schema.progress';

    protected int|float|Closure $value = 0;

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function value(int|float|Closure $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): int|float
    {
        $value = $this->evaluate($this->value);

        return is_int($value) || is_float($value) ? $value : 0;
    }
}
