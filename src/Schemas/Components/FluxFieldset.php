<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Concerns\HasContainerGridLayout;

class FluxFieldset extends Component
{
    use HasContainerGridLayout;

    protected string $view = 'filament-flux::components.schema.fieldset';

    protected string|Closure|null $legend = null;

    public static function make(string|Closure|null $legend = null): static
    {
        $static = app(static::class);

        if ($legend !== null) {
            $static->legend($legend);
        }

        $static->configure();

        return $static;
    }

    public function legend(string|Closure|null $legend): static
    {
        $this->legend = $legend;

        return $this;
    }

    public function getLegend(): ?string
    {
        return $this->evaluate($this->legend);
    }
}
