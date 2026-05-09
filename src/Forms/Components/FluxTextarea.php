<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Closure;
use Filament\Forms\Components\Textarea;

class FluxTextarea extends Textarea
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.textarea';

    protected string|Closure $resize = 'vertical';

    /**
     * @param  'none'|'both'|'horizontal'|'vertical'|Closure  $resize
     */
    public function resize(string|Closure $resize = 'vertical'): static
    {
        $this->resize = $resize;

        return $this;
    }

    public function getResize(): string
    {
        return (string) $this->evaluate($this->resize);
    }
}
