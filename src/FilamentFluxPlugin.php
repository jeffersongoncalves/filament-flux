<?php

namespace Jeffersongoncalves\FilamentFlux;

use Filament\Contracts\Plugin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\OneTimeCodeInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxCheckbox;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxCheckboxGroup;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxInput;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxOtpInput;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxRadio;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxSelect;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxSwitch;
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxTextarea;
use Jeffersongoncalves\FilamentFlux\Support\AssetInjector;

class FilamentFluxPlugin implements Plugin
{
    /**
     * Map of every Filament Form Field that auto-bind supports.
     *
     * Keyed by an arbitrary slug so users can opt-in/out per field with
     * `useEverywhere(['select' => false])`.
     *
     * @var array<string, array{from: class-string, to: class-string}>
     */
    public const FIELD_BINDINGS = [
        'input' => ['from' => TextInput::class, 'to' => FluxInput::class],
        'textarea' => ['from' => Textarea::class, 'to' => FluxTextarea::class],
        'select' => ['from' => Select::class, 'to' => FluxSelect::class],
        'checkbox' => ['from' => Checkbox::class, 'to' => FluxCheckbox::class],
        'checkboxList' => ['from' => CheckboxList::class, 'to' => FluxCheckboxGroup::class],
        'radio' => ['from' => Radio::class, 'to' => FluxRadio::class],
        'toggle' => ['from' => Toggle::class, 'to' => FluxSwitch::class],
        'otp' => ['from' => OneTimeCodeInput::class, 'to' => FluxOtpInput::class],
    ];

    protected ?string $scopeClass = 'filament-flux-scope';

    protected bool $injectAppearance = true;

    protected bool $injectScripts = true;

    /**
     * Per-field auto-bind toggle. `null` disables the entire feature; an
     * array maps each binding slug (see FIELD_BINDINGS) to a boolean.
     *
     * @var array<string, bool>|null
     */
    protected ?array $useEverywhere = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(static::getPluginId());

        return $plugin;
    }

    public function getId(): string
    {
        return static::getPluginId();
    }

    public static function getPluginId(): string
    {
        return 'filament-flux';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::HEAD_END,
            fn (): Htmlable => new HtmlString(AssetInjector::appearance($this)),
        );

        $panel->renderHook(
            PanelsRenderHook::BODY_END,
            fn (): Htmlable => new HtmlString(AssetInjector::scripts($this)),
        );

        if ($this->scopeClass !== null) {
            $panel->renderHook(
                PanelsRenderHook::PAGE_START,
                fn (): Htmlable => new HtmlString(AssetInjector::scopeOpen($this)),
            );

            $panel->renderHook(
                PanelsRenderHook::PAGE_END,
                fn (): Htmlable => new HtmlString(AssetInjector::scopeClose($this)),
            );
        }

        $this->applyContainerBindings();
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function scopeClass(?string $class = 'filament-flux-scope'): static
    {
        $this->scopeClass = $class;

        return $this;
    }

    public function injectAppearance(bool $inject = true): static
    {
        $this->injectAppearance = $inject;

        return $this;
    }

    public function injectScripts(bool $inject = true): static
    {
        $this->injectScripts = $inject;

        return $this;
    }

    /**
     * Replace Filament Form Fields with their Flux equivalents at the
     * container level. Existing Resources continue to call `TextInput::make()`,
     * `Select::make()` etc., but receive `FluxInput`/`FluxSelect` instances.
     *
     * @param  bool|array<string, bool>  $config  Pass `true` to enable all
     *                                            bindings, `false` to disable, or a partial array keyed by slug
     *                                            (input, textarea, select, checkbox, checkboxList, radio, toggle, otp).
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

    public function getScopeClass(): ?string
    {
        return $this->scopeClass;
    }

    public function shouldInjectAppearance(): bool
    {
        return $this->injectAppearance;
    }

    public function shouldInjectScripts(): bool
    {
        return $this->injectScripts;
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
