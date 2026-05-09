<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Filament\Forms\Components\CheckboxList;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxCheckboxGroup extends CheckboxList
{
    use HasFluxVariant;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.checkbox-group';

    public function getFluxVariant(): ?string
    {
        return $this->evaluate($this->fluxVariant) ?? 'default';
    }
}
