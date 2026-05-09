<?php

namespace Jeffersongoncalves\FilamentFlux\Infolists\Components;

use Filament\Infolists\Components\TextEntry;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxBadgeStyle;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxBadgeEntry extends TextEntry
{
    use HasFluxBadgeStyle;
    use HasFluxColor;
    use HasFluxIcon;
    use HasFluxSize;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.infolist.badge';
}
