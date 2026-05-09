<?php

namespace Jeffersongoncalves\FilamentFlux\Components;

use Illuminate\Contracts\Support\Htmlable;

/**
 * Helper for `<flux:breadcrumbs>` markup inside Custom Pages.
 *
 * @phpstan-type BreadcrumbItem array{label: string, url?: string|null, icon?: string|null}
 */
class FluxBreadcrumbs implements Htmlable
{
    /**
     * @var array<int, BreadcrumbItem>
     */
    protected array $items = [];

    public static function make(): self
    {
        return new self;
    }

    /**
     * @param  array<int, BreadcrumbItem>  $items
     */
    public function items(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function add(string $label, ?string $url = null, ?string $icon = null): self
    {
        $this->items[] = compact('label', 'url', 'icon');

        return $this;
    }

    /**
     * @return array<int, BreadcrumbItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function toHtml(): string
    {
        return view('filament-flux::components.breadcrumbs', [
            'items' => $this->items,
        ])->render();
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }
}
