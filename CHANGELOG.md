# Changelog

All notable changes to `filament-flux` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 1.2.0 - 2026-05-09

### Highlights

#### `useFluxNavigation()` — replace Filament's sidebar and topbar items with Flux

Sidebar groups and items render as `<flux:navlist.group>` / `<flux:navlist.item>`. Topbar items render as `<flux:navbar.item>`. Filament's data layer (active state, badges, child items, Resource/Page registration, navigation groups) is preserved verbatim — only the markup changes.

```php
FilamentFluxPlugin::make()->useFluxNavigation();

```
Granular per-area opt-out:

```php
FilamentFluxPlugin::make()->useFluxNavigation([
    'sidebar' => true,
    'topbar' => false,    // keep Filament's topbar items as-is
]);

```
#### Implementation

Each area lives in its own subdirectory under `resources/views/panels-overrides/{sidebar,topbar}`. The toggle prepends the relevant directories to the `filament-panels` view namespace via `View::prependNamespace`.

Missing files fall back to the vendor copies, so upgrading Filament minors stays safe as long as the overridden views (sidebar `item`, sidebar `group`, topbar `item`) match the prop signatures of the active Filament minor.

#### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.1.0

Drop-in. No breaking changes. The new feature is fully opt-in via `useFluxNavigation()`.

## 1.1.0 - 2026-05-09

### Highlights

#### `useEverywhere()` — auto-replace Filament Form Fields with Flux

Existing Resources keep calling `TextInput::make()`, `Select::make()`, etc. — they receive the matching Flux subclass automatically. No code changes inside Resources.

```php
FilamentFluxPlugin::make()->useEverywhere();


```
Granular per-field opt-out:

```php
FilamentFluxPlugin::make()->useEverywhere([
    'select' => false,    // keep Filament's <select> with client-side searchable, etc.
    'otp' => false,
]);


```
Eight bindings registered when enabled:

| Slug | Filament class | Flux replacement |
|---|---|---|
| `input` | `TextInput` | `FluxInput` |
| `textarea` | `Textarea` | `FluxTextarea` |
| `select` | `Select` | `FluxSelect` |
| `checkbox` | `Checkbox` | `FluxCheckbox` |
| `checkboxList` | `CheckboxList` | `FluxCheckboxGroup` |
| `radio` | `Radio` | `FluxRadio` |
| `toggle` | `Toggle` | `FluxSwitch` |
| `otp` | `OneTimeCodeInput` | `FluxOtpInput` |

Each Flux subclass extends the matching Filament native, so the full upstream DSL (`autocomplete()`, `mask()`, `revealable()`, `searchable()`, `relationship()`, etc.) keeps working — only the rendered markup changes.

#### Internal

- `FluxOtpInput` now extends `Filament\Forms\Components\OneTimeCodeInput` (previously `TextInput`), so its container-bind chain matches Filament's class hierarchy. The custom `digits()` helper is gone in favour of the inherited `length()`.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.0.0

No breaking changes for users that don't call `useEverywhere()`. If you wrote code against `FluxOtpInput::digits()` or `getDigits()`, replace with `length()` / `getLength()`.

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