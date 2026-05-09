<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxVariant
{
    protected string|Closure|null $fluxVariant = null;

    public function fluxVariant(string|Closure|null $variant): static
    {
        $this->fluxVariant = $variant;

        return $this;
    }

    public function getFluxVariant(): ?string
    {
        return $this->evaluate($this->fluxVariant);
    }
}
