<?php

namespace Jeffersongoncalves\FilamentFlux\Components;

/**
 * Helper to build payloads for Flux's toast event system.
 *
 * Usage from a Livewire component:
 *
 *     $this->dispatch(...FluxToast::success('Saved!')->dispatchArgs());
 */
class FluxToast
{
    public function __construct(
        protected ?string $heading = null,
        protected ?string $text = null,
        protected ?string $variant = null,
        protected ?int $duration = null,
        protected ?string $position = null,
    ) {}

    public static function make(?string $text = null): self
    {
        return new self(text: $text);
    }

    public static function success(?string $text = null): self
    {
        return new self(text: $text, variant: 'success');
    }

    public static function warning(?string $text = null): self
    {
        return new self(text: $text, variant: 'warning');
    }

    public static function danger(?string $text = null): self
    {
        return new self(text: $text, variant: 'danger');
    }

    public static function info(?string $text = null): self
    {
        return new self(text: $text, variant: 'info');
    }

    public function heading(?string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function text(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param  'success'|'warning'|'danger'|'info'|null  $variant
     */
    public function variant(?string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function duration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function position(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'heading' => $this->heading,
            'text' => $this->text,
            'variant' => $this->variant,
            'duration' => $this->duration,
            'position' => $this->position,
        ], fn ($v) => $v !== null);
    }

    /**
     * Returns ['toast-show', ['detail' => [...]]] for use with `dispatch(...)`.
     *
     * @return array{0: string, 1: array<string, mixed>}
     */
    public function dispatchArgs(): array
    {
        return ['toast-show', $this->toPayload()];
    }
}
