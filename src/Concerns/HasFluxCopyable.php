<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxCopyable
{
    protected bool|Closure $fluxCopyable = false;

    public function fluxCopyable(bool|Closure $copyable = true): static
    {
        $this->fluxCopyable = $copyable;

        return $this;
    }

    public function isFluxCopyable(): bool
    {
        return (bool) $this->evaluate($this->fluxCopyable);
    }
}
