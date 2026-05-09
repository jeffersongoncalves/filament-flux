<?php

namespace Jeffersongoncalves\FilamentFlux\Tables\Columns;

use Filament\Tables\Columns\IconColumn;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;

class FluxIconColumn extends IconColumn
{
    use HasFluxColor;
    use HasFluxIcon;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.table.icon-column';
}
