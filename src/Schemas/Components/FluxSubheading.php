<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;

class FluxSubheading extends Component
{
    protected string $view = 'filament-flux::components.schema.subheading';

    protected string|Closure|null $size = null;

    protected string|Closure|null $content = null;

    public static function make(string|Closure $content): static
    {
        $static = app(static::class);
        $static->content($content);
        $static->configure();

        return $static;
    }

    public function content(string|Closure $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function size(string|Closure|null $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getContent(): string
    {
        return (string) $this->evaluate($this->content);
    }

    public function getSize(): ?string
    {
        return $this->evaluate($this->size);
    }
}
