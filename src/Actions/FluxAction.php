<?php

namespace Jeffersongoncalves\FilamentFlux\Actions;

use Filament\Actions\Action;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxKbd;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxLoading;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxTooltip;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxAction extends Action
{
    use HasFluxIcon;
    use HasFluxKbd;
    use HasFluxLoading;
    use HasFluxSize;
    use HasFluxTooltip;
    use HasFluxVariant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultView('filament-flux::components.action');
    }
}
