<?php

namespace Jeffersongoncalves\FilamentFlux\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

/**
 * Fluent helper for `<flux:modal>` markup inside Custom Pages.
 *
 * @phpstan-type ModalVariant 'default'|'flyout'|'floating'|'bare'
 * @phpstan-type ModalPosition 'top'|'right'|'bottom'|'left'
 */
class FluxModal implements Htmlable
{
    protected string $name;

    protected string $content = '';

    protected string $trigger = '';

    protected ?string $variant = null;

    protected ?string $position = null;

    protected bool $dismissible = true;

    protected bool $closable = true;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function content(string|Htmlable $content): self
    {
        $this->content = $content instanceof Htmlable ? $content->toHtml() : $content;

        return $this;
    }

    public function trigger(string|Htmlable $trigger): self
    {
        $this->trigger = $trigger instanceof Htmlable ? $trigger->toHtml() : $trigger;

        return $this;
    }

    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function position(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function dismissible(bool $dismissible = true): self
    {
        $this->dismissible = $dismissible;

        return $this;
    }

    public function closable(bool $closable = true): self
    {
        $this->closable = $closable;

        return $this;
    }

    /**
     * Livewire dispatch payload that opens this modal in the browser.
     *
     * @return array{name: string, action: string, modal: string}
     */
    public static function openEvent(string $name): array
    {
        return [
            'name' => 'modal-show',
            'action' => 'show',
            'modal' => $name,
        ];
    }

    /**
     * Livewire dispatch payload that closes this modal in the browser.
     *
     * @return array{name: string, action: string, modal: string}
     */
    public static function closeEvent(string $name): array
    {
        return [
            'name' => 'modal-close',
            'action' => 'close',
            'modal' => $name,
        ];
    }

    public function toHtml(): string
    {
        return view('filament-flux::components.modal', [
            'name' => $this->name,
            'content' => new HtmlString($this->content),
            'trigger' => $this->trigger !== '' ? new HtmlString($this->trigger) : null,
            'variant' => $this->variant,
            'position' => $this->position,
            'dismissible' => $this->dismissible,
            'closable' => $this->closable,
        ])->render();
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }
}
