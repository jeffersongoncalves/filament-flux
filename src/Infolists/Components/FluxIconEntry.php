<?php

namespace Jeffersongoncalves\FilamentFlux\Infolists\Components;

use Filament\Infolists\Components\IconEntry;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;

class FluxIconEntry extends IconEntry
{
    use HasFluxColor;
    use HasFluxIcon;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.infolist.icon';
}
