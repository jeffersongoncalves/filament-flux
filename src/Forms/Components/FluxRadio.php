<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Filament\Forms\Components\Radio;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxRadio extends Radio
{
    use HasFluxVariant;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.radio-group';

    public function getFluxVariant(): ?string
    {
        return $this->evaluate($this->fluxVariant) ?? 'default';
    }
}
