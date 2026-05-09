<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxLoading
{
    protected bool|string|Closure|null $fluxLoading = null;

    /**
     * Toggle Flux's native loading state.
     *
     * Pass `true` to always show loading, `false` to never, or a wire:target
     * string to scope the spinner to a specific Livewire request.
     */
    public function fluxLoading(bool|string|Closure|null $loading = true): static
    {
        $this->fluxLoading = $loading;

        return $this;
    }

    public function getFluxLoading(): bool|string|null
    {
        $value = $this->evaluate($this->fluxLoading);

        if ($value === null || is_bool($value) || is_string($value)) {
            return $value;
        }

        return (bool) $value;
    }
}
