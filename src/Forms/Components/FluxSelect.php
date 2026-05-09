<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Filament\Forms\Components\Select;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxSelect extends Select
{
    use HasFluxSize;
    use HasFluxVariant;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.select';

    public function getFluxVariant(): ?string
    {
        return $this->evaluate($this->fluxVariant) ?? 'default';
    }
}
