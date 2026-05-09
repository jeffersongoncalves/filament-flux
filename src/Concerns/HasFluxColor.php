<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

use Closure;

trait HasFluxColor
{
    /**
     * @var string|array<string,string>|Closure|null
     */
    protected string|array|Closure|null $fluxColor = null;

    /**
     * Set the Flux color.
     *
     * Accepts a fixed string ("lime"), an array map (state => color),
     * or a closure receiving ($state, $record).
     *
     * @param  string|array<string,string>|Closure|null  $color
     */
    public function fluxColor(string|array|Closure|null $color): static
    {
        $this->fluxColor = $color;

        return $this;
    }

    public function getFluxColor(mixed $state = null, mixed $record = null): ?string
    {
        if ($this->fluxColor === null) {
            return null;
        }

        if (is_string($this->fluxColor)) {
            return $this->fluxColor;
        }

        if (is_array($this->fluxColor)) {
            if (! is_scalar($state)) {
                return null;
            }

            $key = (string) $state;

            return $this->fluxColor[$key] ?? null;
        }

        return $this->evaluate($this->fluxColor, [
            'state' => $state,
            'record' => $record,
        ]);
    }
}
