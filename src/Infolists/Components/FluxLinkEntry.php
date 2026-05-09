<?php

namespace Jeffersongoncalves\FilamentFlux\Infolists\Components;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxVariant;

class FluxLinkEntry extends TextEntry
{
    use HasFluxVariant;

    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.infolist.link';

    protected string|Closure|null $href = null;

    protected bool|Closure $external = false;

    public function href(string|Closure|null $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function external(bool|Closure $external = true): static
    {
        $this->external = $external;

        return $this;
    }

    public function getHref(mixed $state = null, mixed $record = null): ?string
    {
        return $this->evaluate($this->href, [
            'state' => $state,
            'record' => $record,
        ]);
    }

    public function isExternal(): bool
    {
        return (bool) $this->evaluate($this->external);
    }
}
