<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Filament\Forms\Components\TextInput;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxClearable;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxCopyable;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxKbd;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxInput extends TextInput
{
    use HasFluxClearable;
    use HasFluxCopyable;
    use HasFluxIcon;
    use HasFluxKbd;
    use HasFluxSize;
    use HasFluxVariant;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.input';
}
