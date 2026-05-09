<?php

namespace Jeffersongoncalves\FilamentFlux\Infolists\Components;

use Filament\Infolists\Components\TextEntry;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxTextEntry extends TextEntry
{
    use HasFluxColor;
    use HasFluxSize;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.infolist.text';
}
