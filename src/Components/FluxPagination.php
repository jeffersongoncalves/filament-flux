<?php

namespace Jeffersongoncalves\FilamentFlux\Components;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Helper that renders Flux's pagination component for a Laravel Paginator.
 */
class FluxPagination implements Htmlable
{
    public function __construct(protected Paginator $paginator) {}

    public static function make(Paginator $paginator): self
    {
        return new self($paginator);
    }

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    public function toHtml(): string
    {
        return view('filament-flux::components.pagination', [
            'paginator' => $this->paginator,
        ])->render();
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }
}
