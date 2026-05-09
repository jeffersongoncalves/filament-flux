<?php

namespace Jeffersongoncalves\FilamentFlux\Tests\Fixtures;

use Livewire\Component;

class PlainComponent extends Component
{
    public string $message = 'hello';

    public function render(): string
    {
        return '<div>{{ $message }}</div>';
    }
}
