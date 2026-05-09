<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxIcon
{
    protected string|Closure|null $fluxIcon = null;

    protected string|Closure|null $fluxIconTrailing = null;

    protected string|Closure|null $fluxIconVariant = null;

    public function fluxIcon(string|Closure|null $icon): static
    {
        $this->fluxIcon = $icon;

        return $this;
    }

    public function fluxIconTrailing(string|Closure|null $icon): static
    {
        $this->fluxIconTrailing = $icon;

        return $this;
    }

    public function fluxIconVariant(string|Closure|null $variant): static
    {
        $this->fluxIconVariant = $variant;

        return $this;
    }

    public function getFluxIcon(): ?string
    {
        return $this->evaluate($this->fluxIcon);
    }

    public function getFluxIconTrailing(): ?string
    {
        return $this->evaluate($this->fluxIconTrailing);
    }

    public function getFluxIconVariant(): ?string
    {
        return $this->evaluate($this->fluxIconVariant);
    }
}
