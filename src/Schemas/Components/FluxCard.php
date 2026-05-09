<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Filament\Schemas\Components\Component;

class FluxCard extends Component
{
    protected string $view = 'filament-flux::components.schema.card';

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }
}
