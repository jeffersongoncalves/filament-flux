# Changelog

All notable changes to `filament-flux` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 1.9.4 - 2026-05-10

### Fixed

- **`section` slug — ParseError on every page that uses Filament Sections.** The override rendered `<x-flux::card>` with inline `@if ($persistCollapsed) ... @else ... @endif` Blade directives sitting inside the `x-data` attribute value. Filament's upstream `<section>` element tolerates that because Blade's HTML parser compiles directives inside attribute strings, but Blade's anonymous-component parser leaves them as literal text — the leaked `@endif` then surfaces as `syntax error, unexpected token "endif", expecting end of file`. The Alpine payload (and every collapsible event handler) is now pre-built into a `ComponentAttributeBag` and passed to `<x-flux::card>` via `:attributes`, so the component tag only ever sees plain attribute strings.
- **Mobile and desktop sidebar toggle buttons rendering simultaneously on desktop.** Filament's `lg:hidden` rule on `fi-topbar-close-sidebar-btn` is a single-class selector with the same specificity as Flux's `inline-flex` utility on `<flux:button>`, so a source-order race could leave both the mobile X and the desktop chevron visible at `>=lg`. Plugin CSS now forces `display: none !important` on the mobile open/close pair at `>=lg` and on the desktop collapse pair (and its container) at `<lg`, so the cascade stops mattering.

## 1.9.3 - 2026-05-10

### Fixed

- **Shell sidebar collapse + duplicate topbar buttons.** When the panel uses `fullyCollapsibleOnDesktop()` and `useFluxNavigation(['shell' => true])` was on, the sidebar slid entirely off-screen instead of compacting to icons, and the topbar rendered both `fi-topbar-open-sidebar-btn` and `fi-topbar-close-collapse-sidebar-btn` at once. Shell now maps either Filament collapsibility intent (`collapsibleOnDesktop` and `fullyCollapsibleOnDesktop`) to Flux's compact mode: `<flux:sidebar>` opens with `collapsible="true"` so Flux owns desktop + mobile toggling, and the layout adds the `fi-body-has-sidebar-collapsible-on-desktop` body class whenever any collapse mode is active so Filament's upstream sidebar/topbar CSS stays aligned with the visual we ship.
- **Modal close button on the wrong (leading) edge.** Filament's `.fi-modal-close-btn` pins to the trailing edge via `position: absolute; inset-inline-end`; Flux's `<flux:button>` injects a `relative` class with utility-layer specificity that, depending on stylesheet order, can win and leave the X stuck inside the header's flex flow on the leading edge. Plugin CSS now re-asserts `position: absolute !important`, `inset-block-start`, and `inset-inline-end` for both standard and slide-over modals so the X stays on the trailing edge regardless of source order.

## 1.9.2 - 2026-05-10

### Fixed

- **Empty rows between sidebar groups.** Filament's `.fi-sidebar-nav-groups` ships with `gap-y-7` to space top-level groups; inside `<flux:navlist>` that gap stacked on top of the navlist's own rhythm and rendered as visible holes between sibling items. Plugin CSS now forces `gap: 0` on `.fi-sidebar-nav-groups` so the Flux navlist owns the spacing.

## 1.9.1 - 2026-05-10

### Fixed

- **Shell sidebar toggle button no longer dead.** When the `useFluxNavigation(['shell' => true])` override was on, Filament's topbar `fi-topbar-open-sidebar-btn` (and its close/collapse twins) drove `$store.sidebar` while the rendered sidebar was a Flux `<ui-sidebar>` — so clicking the menu icon did nothing visually. Plugin now ships a small bridge that subscribes to the Filament Alpine store via `effect()`, dispatches Flux's documented `flux-sidebar-toggle` event whenever store and element disagree, and mirrors the inverse direction with a MutationObserver on the `data-flux-sidebar-collapsed-{mobile,desktop}` attributes (so backdrop clicks and `<ui-sidebar-toggle>` elements update the Filament store too). Reattaches on `livewire:navigated`. No-ops when `<ui-sidebar>` is absent.

## 1.9.0 - 2026-05-10

### Added

- **Phase H5 — `statsCard` Blade override.** New slug for `useFluxComponents()` swaps the `<x-filament-widgets::stats-overview-widget.stat>` view for a Flux-native implementation:
  
  - `<flux:card>` wraps the stat,
  - `<flux:subheading>` carries the leading icon + label,
  - `<flux:heading size="xl">` renders the value,
  - `<flux:text>` renders the description (color + before/after icon position preserved).
  
- URL anchor (`<a>` vs `<div>`), Filament polling attribute, the Alpine chart canvas (`x-load`/`x-data="statsOverviewStatChart(...)"`), and every `fi-wi-stats-overview-stat*` BEM class are kept so existing CSS keeps working alongside the Flux skin.
  

### Usage

```php
FilamentFluxPlugin::make()->useFluxComponents([
    'statsCard' => true,
]);





```
Disable the slug if you depend on Filament-only stat affordances (array-tuple description colors, custom view per stat, etc.).

## 1.8.1 - 2026-05-10

### Fixed

- **HeroiconNormalizer** now recognizes Filament v5 `Heroicon` backed-enum values (`o-bars-3`, `s-cog`, `m-trash`, `c-bell`, plus `mini-`/`micro-`/`outline-`/`solid-` long forms). Previously these bare prefixes leaked into `<flux:icon>` and threw `Flux component [icon.o-bars-3] does not exist` from sidebar/topbar/icon-button overrides.
- `HeroiconNormalizer::variant()` resolves the Flux variant from the same prefixes so heroicons render with the correct stroke/fill style.

## 1.8.0 - 2026-05-10

### Highlights

#### Phase H4 — schemaText slug + HeroiconNormalizer for mixed icon sets

A new opt-in slug that targets a different Filament subpackage namespace, plus a normalizer that bridges Filament's Blade Icons naming with Flux's heroicon-only component registry.

```php
FilamentFluxPlugin::make()->useFluxComponents([
    'schemaText' => true,
]);







```
| Slug | Filament view | Flux replacement |
|---|---|---|
| `schemaText` | `filament-schemas::components.text` | `<flux:text>` (badge variant still delegates to `<x-filament::badge>`) |

This is the first slug to target the `filament-schemas` view namespace; previous slugs touched `filament`.

#### `HeroiconNormalizer` — mixed icon sets

Filament users frequently register icons from non-heroicon Blade Icons sets. The bundled normalizer detects known prefixes and routes accordingly:

- `heroicon-{o,s,m,c,mini,micro,outline,solid}-*` → bare name + Flux `variant`
- bare names (no prefix) → assumed heroicon
- `fontawesome-`, `tabler-`, `lucide-`, `phosphor-`, `mdi-`, `octicon-`, `bi-`, `feather-`, `simple-icons-`, `eos-icons-`, `bxl-`, `bxs-`, `bx-`, `gmdi-`, `css-gg-`, `fab-`/`far-`/`fas-` → fall back to `\Filament\Support\generate_icon_html()`

Sidebar and topbar item overrides, the `icon` override, and the `iconButton` override all route through it. Non-heroicon icons are pre-rendered via Filament's Blade Icons helper and handed to `<flux:navlist.item>` / `<flux:navbar.item>` as `HtmlString`, so registered Font Awesome / Tabler / Lucide icons keep rendering inside the Flux navigation.

#### Bug fix

Resolves `Flux component [icon.heroicon-o-document-text] does not exist` when Filament registered icons used the `heroicon-{variant}-*` Blade Icons convention.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.7.0

Drop-in. The `schemaText` slug ships off by default. The HeroiconNormalizer auto-applies inside existing overrides — no API changes required.

### Roadmap

- `1.9.0` — Phase H5: stats overview widget
- `1.10.0` — Phase H6: notifications envelope

## 1.7.0 - 2026-05-10

### Highlights

#### Phase H3 — dropdown header + modal typography

Three new opt-in slugs for `useFluxComponents()`:

```php
FilamentFluxPlugin::make()->useFluxComponents([
    'dropdownHeader' => true,
    'modalHeading' => true,
    'modalDescription' => true,
]);








```
| Slug | Filament view | Flux replacement |
|---|---|---|
| `dropdownHeader` | `filament::components.dropdown.header` | `<flux:heading size="sm">` with optional leading icon |
| `modalHeading` | `filament::components.modal.heading` | `<flux:heading size="lg">` (envelope, events and Action machinery stay on Filament) |
| `modalDescription` | `filament::components.modal.description` | `<flux:text>` |

#### Wholesale modal swap intentionally deferred

`<x-filament::modal>` is wired into:

- `filamentModal` Alpine state (`isOpen`, `isTopmost`, autofocus, slideOver)
- The `open-modal` / `close-modal` / `close-modal-quietly` event protocol with IDs
- Action confirmation machinery (`->requiresConfirmation()` dispatches those events)
- `<x-filament-actions::modals>` teleport wrapper

Replacing it with `<flux:modal>` (different event protocol and state model) would break Action confirmation flows everywhere. The lighter typography swaps in this release land most of the visual benefit without breaking Action modals.

#### Fix: icon helper SVG cast

Resolves a `Object of class BladeUI\Icons\Svg could not be converted to string` error in the `icon` and `iconButton` overrides. `generate_icon_html()` returns `?Htmlable`; the views now go through `->toHtml()` before emitting the SVG instead of relying on `__toString()`.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.6.0

Drop-in. New slugs ship off by default — existing `useFluxComponents()` calls keep their previous behavior. The icon/iconButton SVG fix is backward compatible.

### Roadmap

- `1.8.0` — Phase H4: schema text/list overrides
- `1.9.0` — Phase H5: stats overview widget
- `1.10.0` — Phase H6: notifications envelope

## 1.6.0 - 2026-05-09

### Highlights

#### Phase H2 — wrappers (callout, card, fieldset, section, dropdown)

Five new opt-in slugs for `useFluxComponents()`:

```php
FilamentFluxPlugin::make()->useFluxComponents([
    'callout' => true,
    'card' => true,
    'fieldset' => true,
    'section' => true,
    'dropdown' => true,
]);









```
| Slug | Filament view(s) | Flux replacement |
|---|---|---|
| `callout` | `filament::components.callout` | `<flux:callout>` + `<flux:callout.text>` (variant from color) |
| `card` | `filament::components.card` | `<flux:card>` |
| `fieldset` | `filament::components.fieldset` | `<flux:fieldset>` + `<flux:legend>` |
| `section` | `filament::components.section.index` | `<flux:card>` with header via `<flux:heading>` + `<flux:text>`; collapsible/persist Alpine state preserved |
| `dropdown` | `dropdown` + `dropdown.list` + `dropdown.list.item` | `<flux:dropdown>` + `<flux:menu>` + `<flux:menu.item>` |

Filament `placement` is mapped to flux `position` + `align`. Form-tag dropdown items (CSRF + POST submit) fall back to Filament's native markup automatically.

#### Fix: icon size normalization

The `icon` and `iconButton` overrides now normalize a string `iconSize` into a `Filament\Support\Enums\IconSize` enum before delegating to `generate_icon_html()`. Resolves a TypeError when third-party packages pass icon sizes as plain strings.

### Compatibility

| Filament | Laravel | PHP | Livewire | Flux |
|---|---|---|---|---|
| ^5.0 | ^11 / ^12 / ^13 | ^8.2 | ^4.0 | ^2.14 |

### Upgrading from 1.5.0

Drop-in. New slugs ship off by default — existing `useFluxComponents()` calls keep their previous behavior. The `icon`/`iconButton` size fix is backward compatible.

### Roadmap

- `1.7.0` — Phase H3: action modal envelope + filament-actions:group
- `1.8.0` — Phase H4: schema text/list overrides
- `1.9.0` — Phase H5: stats overview widget

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