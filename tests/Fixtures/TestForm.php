<?php

namespace Jeffersongoncalves\FilamentFlux\Tests\Fixtures;

use Closure;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

class TestForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public array $data = [];

    public static ?Closure $fieldsCallback = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $components = static::$fieldsCallback ? (static::$fieldsCallback)() : [];

        return $schema
            ->components($components)
            ->statePath('data');
    }

    public function save(): array
    {
        return $this->form->getState();
    }

    public function render()
    {
        return view('test-form');
    }
}
