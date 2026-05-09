<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxBadgeStyle
{
    protected string|Closure|null $fluxBadgeVariant = null;

    /**
     * @param  'solid'|'pill'|null|Closure  $variant
     */
    public function fluxBadgeVariant(string|Closure|null $variant): static
    {
        $this->fluxBadgeVariant = $variant;

        return $this;
    }

    public function getFluxBadgeVariant(): ?string
    {
        return $this->evaluate($this->fluxBadgeVariant);
    }
}
