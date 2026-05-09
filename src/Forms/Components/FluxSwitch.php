<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Closure;
use Filament\Forms\Components\Toggle;

class FluxSwitch extends Toggle
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.switch';

    protected string|Closure $align = 'right';

    protected function setUp(): void
    {
        parent::setUp();

        // <flux:switch> renders its own inline label, so hide the outer
        // Filament label by default to avoid duplication. Users can opt
        // back in via `hiddenLabel(false)`.
        $this->hiddenLabel();
    }

    /**
     * @param  'left'|'right'|Closure  $align
     */
    public function fluxAlign(string|Closure $align): static
    {
        $this->align = $align;

        return $this;
    }

    public function getFluxAlign(): string
    {
        return (string) $this->evaluate($this->align);
    }
}
