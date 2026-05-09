<?php

namespace Jeffersongoncalves\FilamentFlux\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;

class FluxCallout extends Component
{
    use HasFluxColor;
    use HasFluxIcon;

    protected string $view = 'filament-flux::components.schema.callout';

    protected string|Closure|null $heading = null;

    protected string|Closure|null $text = null;

    protected string|Closure|null $variant = null;

    protected bool|Closure $inline = false;

    public static function make(?string $name = null): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function heading(string|Closure|null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function text(string|Closure|null $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param  'success'|'danger'|'warning'|'secondary'|null|Closure  $variant
     */
    public function variant(string|Closure|null $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function inline(bool|Closure $inline = true): static
    {
        $this->inline = $inline;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->evaluate($this->heading);
    }

    public function getText(): ?string
    {
        return $this->evaluate($this->text);
    }

    public function getVariant(): ?string
    {
        return $this->evaluate($this->variant);
    }

    public function isInline(): bool
    {
        return (bool) $this->evaluate($this->inline);
    }
}
