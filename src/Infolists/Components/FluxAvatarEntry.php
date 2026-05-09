<?php

namespace Jeffersongoncalves\FilamentFlux\Infolists\Components;

use Filament\Infolists\Components\ImageEntry;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxAvatar;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxAvatarEntry extends ImageEntry
{
    use HasFluxAvatar;
    use HasFluxColor;
    use HasFluxSize;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.infolist.avatar';
}
