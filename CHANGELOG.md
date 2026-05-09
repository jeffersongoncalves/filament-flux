# Changelog

All notable changes to `filament-flux` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 1.5.0 - 2026-05-09

### Highlights

#### `useFluxComponents()` — Filament Blade component overrides (Phase H1)

A new opt-in API alongside `useEverywhere()` and `useFluxNavigation()`. Six atomic `<x-filament::*>` Blade components are now swappable for `<x-flux::*>` markup at runtime via `View::prependNamespace`.

```php
FilamentFluxPlugin::make()->useFluxComponents();

// granular:
FilamentFluxPlugin::make()->useFluxComponents([
    'badge' => true,
    'avatar' => true,
    'icon' => true,
    'iconButton' => false,    // keep Filament for icon buttons with key bindings
    'link' => true,
    'breadcrumbs' => true,
]);

```
| Slug | Filament view | Flux replacement |
|---|---|---|
| `badge` | `filament::components.badge` | `<flux:badge>` (color map: primary → blue, success → lime, warning → amber, danger → red, info → cyan, gray → zinc) |
| `avatar` | `filament::components.avatar` | `<flux:avatar>` |
| `icon` | `filament::components.icon` | `<flux:icon>` (falls back to native HTML for non-string icons) |
| `iconButton` | `filament::components.icon-button` | `<flux:button square icon>` |
| `link` | `filament::components.link` | `<flux:link>` |
| `breadcrumbs` | `filament::components.breadcrumbs` | `<flux:breadcrumbs>` + `<flux:breadcrumbs.item>` |

Filament-specific affordances (delete buttons on badges, key bindings, loading indicators) don't fully map to Flux primitives — disable a slug if you rely on them.

#### `pagination` slug deferred

Filament's pagination handles cursor paginators and per-page selectors that `<flux:pagination>` doesn't cover in the free tier. Will land in a later H2 release alongside the schema/widget overrides.

#### Fix: inline checkbox / switch layout

`FluxCheckbox` and `FluxSwitch` views now honor Filament's `isInline()` flag and emit the inner control inside the `<x-slot name="labelPrefix">` slot when inline is true. Restores horizontal layout (label beside control instead of below).

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.4.0

Drop-in. `useFluxComponents()` is a new feature — existing `useEverywhere()` and `useFluxNavigation()` calls keep their previous behavior.

## 1.4.0 - 2026-05-09

### Highlights

#### `useFluxNavigation(['themeSwitcher' => true])` — Flux theme dropdown

Off by default. When opted in, Filament's three-button theme switcher (`<x-filament-panels::theme-switcher>`) is replaced by a single `<flux:dropdown>` carrying a `<flux:menu>` of light / dark / system.

```php
FilamentFluxPlugin::make()->useFluxNavigation([
    'themeSwitcher' => true,
]);


```
The trigger reflects the current selection. Filament's `theme-changed` event is dispatched on selection, so the existing dark-mode Alpine store listener and the Phase 1 bridge into `flux.appearance` keep working unchanged.

#### Notifications and user menu — kept as-is

Filament's notifications system and full user menu use deep DSL surfaces (Notification objects with broadcast channels, sortable Action items, profile slots, embedded theme switcher) that don't map cleanly onto Flux primitives. The recommended path:

- For Flux-style toasts in Custom Pages, use `FluxToast::dispatchArgs()` to dispatch `toast-show` Livewire events.
- Filament's user menu remains the most flexible affordance for tenant menus, profile links, and dynamic items.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.3.0

Drop-in. The new slug ships off by default — existing `useFluxNavigation()` calls keep their previous behavior.

### Roadmap

This release closes the auto-replacement plan (Fase G3). Future minors will track Filament v5.x view changes and add Pro-only Flux components when filament-flux-pro lands.

## 1.3.0 - 2026-05-09

### Highlights

#### `useFluxNavigation(['shell' => true])` — full Flux panel shell

Off by default. When the new `shell` slug is opted in, the plugin replaces two more Filament views to render the panel's structural shell with Flux primitives:

- `filament-panels::livewire.sidebar` — the panel's `<aside class="fi-sidebar">` becomes `<flux:sidebar collapsible sticky>` with `<flux:sidebar.header>`, `<flux:navlist>` and `<flux:spacer>` wrappers. Tenant menu, global search, render hooks and the user/notifications footer are preserved.
- `filament-panels::components.layout.index` — the inner `<main>` element becomes `<x-flux::main>`. Render hooks (`CONTENT_*`, `FOOTER`, `LAYOUT_*`) keep firing in the same positions.

```php
FilamentFluxPlugin::make()->useFluxNavigation([
    'sidebar' => true,    // sidebar item/group → flux:navlist (already in 1.2.0)
    'topbar' => true,     // topbar item → flux:navbar.item (already in 1.2.0)
    'shell' => true,      // NEW: panel shell → flux:sidebar + flux:main
]);



```
The default `useFluxNavigation()` call (no args) still ships with `shell => false` so panels can adopt the lighter sidebar/topbar item overrides without touching the structural shell.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.2.0

Drop-in. Existing `useFluxNavigation()` calls keep the same behavior because `shell` defaults to false.

### Roadmap

- `1.4.0` — Fase G3: replace Filament notifications with `<flux:toast>`, opt-in modal/theme switcher overrides.

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