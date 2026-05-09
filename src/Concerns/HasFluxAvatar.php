<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxAvatar
{
    protected string|Closure|null $fluxSrc = null;

    protected string|Closure|null $fluxName = null;

    protected string|Closure|null $fluxBadge = null;

    public function fluxSrc(string|Closure|null $src): static
    {
        $this->fluxSrc = $src;

        return $this;
    }

    public function fluxName(string|Closure|null $name): static
    {
        $this->fluxName = $name;

        return $this;
    }

    /**
     * Status dot color displayed on the avatar.
     */
    public function fluxBadge(string|Closure|null $badge): static
    {
        $this->fluxBadge = $badge;

        return $this;
    }

    public function getFluxSrc(mixed $state = null, mixed $record = null): ?string
    {
        return $this->evaluate($this->fluxSrc, [
            'state' => $state,
            'record' => $record,
        ]);
    }

    public function getFluxName(mixed $state = null, mixed $record = null): ?string
    {
        return $this->evaluate($this->fluxName, [
            'state' => $state,
            'record' => $record,
        ]);
    }

    public function getFluxBadge(mixed $state = null, mixed $record = null): ?string
    {
        return $this->evaluate($this->fluxBadge, [
            'state' => $state,
            'record' => $record,
        ]);
    }
}
