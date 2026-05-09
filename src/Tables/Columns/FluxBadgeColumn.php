<?php

namespace Jeffersongoncalves\FilamentFlux\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxBadgeStyle;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxBadgeColumn extends TextColumn
{
    use HasFluxBadgeStyle;
    use HasFluxColor;
    use HasFluxIcon;
    use HasFluxSize;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.table.badge-column';
}
