<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxTooltip
{
    protected string|Closure|null $fluxTooltip = null;

    protected string|Closure $fluxTooltipPosition = 'top';

    /**
     * Attach a Flux tooltip to the component.
     *
     * @param  'top'|'right'|'bottom'|'left'|Closure  $position
     */
    public function fluxTooltip(string|Closure|null $tooltip, string|Closure $position = 'top'): static
    {
        $this->fluxTooltip = $tooltip;
        $this->fluxTooltipPosition = $position;

        return $this;
    }

    public function getFluxTooltip(): ?string
    {
        return $this->evaluate($this->fluxTooltip);
    }

    public function getFluxTooltipPosition(): string
    {
        return (string) $this->evaluate($this->fluxTooltipPosition);
    }
}
