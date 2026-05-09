<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxSize
{
    protected string|Closure|null $fluxSize = null;

    /**
     * @param  'xs'|'sm'|'base'|'lg'|null|Closure  $size
     */
    public function fluxSize(string|Closure|null $size): static
    {
        $this->fluxSize = $size;

        return $this;
    }

    public function getFluxSize(): ?string
    {
        return $this->evaluate($this->fluxSize);
    }
}
