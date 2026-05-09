# Changelog

All notable changes to `filament-flux` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 1.0.0 - 2026-05-09

Initial release of **filament-flux** for Filament v5.

### What's included

#### Form Fields

`FluxInput`, `FluxTextarea`, `FluxSelect`, `FluxCheckbox`, `FluxCheckboxGroup`, `FluxRadio`, `FluxRadioGroup`, `FluxSwitch`, `FluxOtpInput`. Each extends the matching Filament native (TextInput/Textarea/Select/Checkbox/CheckboxList/Radio/Toggle) so the full upstream DSL — `autocomplete()`, `mask()`, `length()`, `revealable()`, `step()`, `extraInputAttributes()`, etc. — is inherited verbatim.

#### Actions

`FluxAction` (extends `Filament\Actions\Action`), `FluxLinkAction`, `FluxDropdown` (extends `ActionGroup`). DSL: `fluxVariant()`, `fluxIcon()`, `fluxKbd()`, `fluxLoading()`, `fluxTooltip()`, `fluxSize()`. Confirmation modals reuse Filament's native modal system.

#### Table Columns

`FluxBadgeColumn` (extends `TextColumn`), `FluxAvatarColumn` (extends `ImageColumn`), `FluxIconColumn` (extends `IconColumn`), `FluxLinkColumn`. Color resolver supports fixed string, state→color array, or closure.

#### Infolist Entries

`FluxBadgeEntry`, `FluxAvatarEntry`, `FluxIconEntry`, `FluxTextEntry`, `FluxLinkEntry`.

#### Schema components

`FluxHeading`, `FluxSubheading`, `FluxSeparator`, `FluxSpacer`, `FluxCallout`, `FluxFieldset`, `FluxCard`, `FluxSkeleton`, `FluxProgress`.

#### Helpers for Custom Pages

`FluxModal`, `FluxBreadcrumbs`, `FluxPagination`, `FluxToast`, `FluxIcon`.

#### Plugin + install

- `FilamentFluxPlugin::make()` registers render hooks for `@fluxAppearance` / `@fluxScripts`, and a JS bridge that keeps Filament's theme switcher and Flux's appearance store in sync (cross-tab via `storage` events).
- `php artisan filament-flux:install --panel=admin` idempotently patches `resources/css/filament/{panel}/theme.css` with the required `@import` and `@source` directives.
- `dist/filament-flux.css` maps Filament's `--color-primary-*` onto Flux's `--color-accent-*` and adds a `:focus-visible` ring sourced from the panel's primary color.

#### Reusable traits

`HasFluxIcon`, `HasFluxClearable`, `HasFluxCopyable`, `HasFluxKbd`, `HasFluxSize`, `HasFluxVariant`, `HasFluxLoading`, `HasFluxTooltip`, `HasFluxColor`, `HasFluxAvatar`, `HasFluxBadgeStyle`.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Install

```bash
composer require jeffersongoncalves/filament-flux
php artisan make:filament-theme admin
php artisan filament-flux:install --panel=admin
npm run build

```
```php
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

return $panel->plugins([
    FilamentFluxPlugin::make(),
]);

```