<?php

namespace Jeffersongoncalves\FilamentFlux\Tables\Columns;

use Filament\Tables\Columns\ImageColumn;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxAvatar;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxAvatarColumn extends ImageColumn
{
    use HasFluxAvatar;
    use HasFluxColor;
    use HasFluxSize;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.table.avatar-column';
}
