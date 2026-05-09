<?php

namespace Jeffersongoncalves\FilamentFlux\Actions;

use Filament\Actions\ActionGroup;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxDropdown extends ActionGroup
{
    use HasFluxIcon;
    use HasFluxSize;
    use HasFluxVariant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultView('filament-flux::components.dropdown');
    }
}
