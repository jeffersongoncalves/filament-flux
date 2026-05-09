<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;

class FluxHeading extends Component
{
    protected string $view = 'filament-flux::components.schema.heading';

    protected string|Closure|null $level = null;

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

    /**
     * @param  '1'|'2'|'3'|'4'|null|Closure  $level
     */
    public function level(string|Closure|null $level): static
    {
        $this->level = $level;

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

    public function getLevel(): ?string
    {
        $level = $this->evaluate($this->level);

        return $level !== null ? (string) $level : null;
    }

    public function getSize(): ?string
    {
        return $this->evaluate($this->size);
    }
}
