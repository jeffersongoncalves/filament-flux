<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxClearable
{
    protected bool|Closure $fluxClearable = false;

    public function fluxClearable(bool|Closure $clearable = true): static
    {
        $this->fluxClearable = $clearable;

        return $this;
    }

    public function isFluxClearable(): bool
    {
        return (bool) $this->evaluate($this->fluxClearable);
    }
}
