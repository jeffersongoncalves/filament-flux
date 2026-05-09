<?php

namespace Jeffersongoncalves\FilamentFlux;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Jeffersongoncalves\FilamentFlux\Support\AssetInjector;

class FilamentFluxPlugin implements Plugin
{
    protected ?string $scopeClass = 'filament-flux-scope';

    protected bool $injectAppearance = true;

    protected bool $injectScripts = true;

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
}
