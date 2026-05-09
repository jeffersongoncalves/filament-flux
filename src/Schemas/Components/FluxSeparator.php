<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;

class FluxSeparator extends Component
{
    protected string $view = 'filament-flux::components.schema.separator';

    protected string|Closure|null $orientation = null;

    protected string|Closure|null $variant = null;

    protected string|Closure|null $text = null;

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function orientation(string|Closure|null $orientation): static
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function variant(string|Closure|null $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function text(string|Closure|null $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->evaluate($this->orientation);
    }

    public function getVariant(): ?string
    {
        return $this->evaluate($this->variant);
    }

    public function getText(): ?string
    {
        return $this->evaluate($this->text);
    }
}
