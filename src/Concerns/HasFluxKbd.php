<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxKbd
{
    protected string|Closure|null $fluxKbd = null;

    public function fluxKbd(string|Closure|null $kbd): static
    {
        $this->fluxKbd = $kbd;

        return $this;
    }

    public function getFluxKbd(): ?string
    {
        return $this->evaluate($this->fluxKbd);
    }
}
