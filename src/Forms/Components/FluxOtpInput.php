<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;

class FluxOtpInput extends TextInput
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.otp';

    protected int|Closure $digits = 6;

    protected bool|Closure $isPrivate = false;

    public function digits(int|Closure $digits): static
    {
        $this->digits = $digits;

        return $this;
    }

    public function private(bool|Closure $private = true): static
    {
        $this->isPrivate = $private;

        return $this;
    }

    public function getDigits(): int
    {
        return (int) $this->evaluate($this->digits);
    }

    public function isPrivate(): bool
    {
        return (bool) $this->evaluate($this->isPrivate);
    }
}
