<?php

namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Filament\Forms\Components\Checkbox;

class FluxCheckbox extends Checkbox
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-flux::components.form.checkbox';

    protected function setUp(): void
    {
        parent::setUp();

        // <flux:checkbox> renders its own inline label, so hide the outer
        // Filament label by default. Users can opt back in via
        // `hiddenLabel(false)`.
        $this->hiddenLabel();
    }
}
