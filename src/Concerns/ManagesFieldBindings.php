<?php

namespace Jeffersongoncalves\FilamentFlux\Concerns;

/**
 * Container-level Filament field → Flux field auto-binding.
 *
 * The consuming plugin must declare a `FIELD_BINDINGS` class constant:
 *
 * @var array<string, array{from: class-string, to: class-string}>
 *
 * keyed by an arbitrary slug. The trait reads it through late static
 * binding (`static::FIELD_BINDINGS`), so filament-flux and filament-flux-pro
 * share this logic while keeping their own field maps.
 */
trait ManagesFieldBindings
{
    /**
     * Per-field auto-bind toggle. `null` disables the entire feature; an
     * array maps each binding slug (see FIELD_BINDINGS) to a boolean.
     *
     * @var array<string, bool>|null
     */
    protected ?array $useEverywhere = null;

    /**
     * Replace Filament Form Fields with their Flux equivalents at the
     * container level. Existing Resources keep calling `TextInput::make()`,
     * `Select::make()` etc., but receive the Flux instances.
     *
     * @param  bool|array<string, bool>  $config  Pass `true` to enable all
     *                                            bindings, `false` to disable, or a partial array keyed by slug.
     */
    public function useEverywhere(bool|array $config = true): static
    {
        if ($config === false) {
            $this->useEverywhere = null;

            return $this;
        }

        $defaults = array_fill_keys(array_keys(static::FIELD_BINDINGS), true);

        if ($config === true) {
            $this->useEverywhere = $defaults;

            return $this;
        }

        $this->useEverywhere = array_merge($defaults, $config);

        return $this;
    }

    /**
     * Slugs of bindings currently active.
     *
     * @return array<int, string>
     */
    public function getActiveBindings(): array
    {
        if ($this->useEverywhere === null) {
            return [];
        }

        return array_keys(array_filter($this->useEverywhere, fn (bool $on): bool => $on));
    }

    protected function applyContainerBindings(): void
    {
        if ($this->useEverywhere === null) {
            return;
        }

        foreach ($this->useEverywhere as $slug => $enabled) {
            if (! $enabled) {
                continue;
            }

            $binding = static::FIELD_BINDINGS[$slug] ?? null;

            if ($binding === null) {
                continue;
            }

            app()->bind($binding['from'], $binding['to']);
        }
    }
}
