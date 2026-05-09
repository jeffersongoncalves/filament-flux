<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;

class FluxSkeleton extends Component
{
    protected string $view = 'filament-flux::components.schema.skeleton';

    protected string|Closure|null $class = null;

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function class(string|Closure|null $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->evaluate($this->class);
    }
}
