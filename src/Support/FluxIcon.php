<?php

namespace Jeffersongoncalves\FilamentFlux\Support;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class FluxIcon implements Htmlable
{
    protected string $name;

    protected ?string $variant = null;

    protected ?string $class = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    /**
     * @param  'outline'|'solid'|'mini'|'micro'|null  $variant
     */
    public function fluxVariant(?string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function class(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function toHtml(): string
    {
        return view('filament-flux::components.icon', [
            'name' => $this->name,
            'variant' => $this->variant,
            'class' => $this->class,
        ])->render();
    }

    public function toHtmlString(): HtmlString
    {
        return new HtmlString($this->toHtml());
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }
}
