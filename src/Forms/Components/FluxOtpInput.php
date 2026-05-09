<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Closure;
use Filament\Forms\Components\OneTimeCodeInput;

class FluxOtpInput extends OneTimeCodeInput
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.otp';

    protected bool|Closure $isPrivate = false;

    public function private(bool|Closure $private = true): static
    {
        $this->isPrivate = $private;

        return $this;
    }

    public function isPrivate(): bool
    {
        return (bool) $this->evaluate($this->isPrivate);
    }
}
