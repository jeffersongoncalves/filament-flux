<?php

namespace Jeffersongoncalves\FilamentFlux\Actions;

use Closure;
use Filament\Actions\Action;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxTooltip;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxLinkAction extends Action
{
    use HasFluxIcon;
    use HasFluxTooltip;
    use HasFluxVariant;

    protected bool|Closure $external = false;

    protected bool|Closure $accent = true;

    protected bool|Closure $strong = false;

    public function external(bool|Closure $external = true): static
    {
        $this->external = $external;

        return $this;
    }

    public function accent(bool|Closure $accent = true): static
    {
        $this->accent = $accent;

        return $this;
    }

    public function strong(bool|Closure $strong = true): static
    {
        $this->strong = $strong;

        return $this;
    }

    public function isExternal(): bool
    {
        return (bool) $this->evaluate($this->external);
    }

    public function isAccent(): bool
    {
        return (bool) $this->evaluate($this->accent);
    }

    public function isStrong(): bool
    {
        return (bool) $this->evaluate($this->strong);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultView('filament-flux::components.link-action');
    }
}
