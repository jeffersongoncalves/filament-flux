<div class="filament-hidden">

![Filament Flux](https://raw.githubusercontent.com/jeffersongoncalves/filament-flux/main/art/jeffersongoncalves-filament-flux.png)

</div>

# Filament Flux

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-flux.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-flux)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-flux.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-flux)
[![Tests](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-flux/tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-flux/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-flux/phpstan.yml?branch=1.x&label=phpstan&style=flat-square)](https://github.com/jeffersongoncalves/filament-flux/actions/workflows/phpstan.yml)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-flux.svg?style=flat-square)](LICENSE.md)

Filament v5 plugin exposing [Livewire Flux](https://fluxui.dev) UI components as native Form Fields, Table Columns, Infolist Entries, and Actions.

> **Filament v5 only.** Single `1.x` branch tracks Filament v5. No backports to v3/v4.

## Compatibility

| Branch | Filament | Laravel       | PHP    | Livewire | Flux  |
|--------|----------|---------------|--------|----------|-------|
| `1.x`  | ^5.0     | ^11 / ^12 / ^13 | ^8.2 | ^4.0     | ^2.14 |

## Installation

```bash
composer require jeffersongoncalves/filament-flux
php artisan make:filament-theme admin
php artisan filament-flux:install --panel=admin
npm run build
```

`filament-flux:install` patches `resources/css/filament/{panel}/theme.css` idempotently:

```css
@import "tailwindcss";
@import "../../../../vendor/livewire/flux/dist/flux.css";
@import "../../../../vendor/jeffersongoncalves/filament-flux/dist/filament-flux.css";
```

Tailwind v4 picks up Flux + this package's view scanning automatically.

## Register the plugin

```php
use Filament\Panel;
use Jeffersongoncalves\FilamentFlux\FilamentFluxPlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugins([
        FilamentFluxPlugin::make(),
    ]);
}
```

### Plugin options

```php
FilamentFluxPlugin::make()
    ->scopeClass('filament-flux-scope')   // wrapper class on every page; pass null to disable
    ->injectAppearance(true)              // controls @fluxAppearance in <head>
    ->injectScripts(true);                // controls @fluxScripts before </body>
```

The plugin also installs a JavaScript bridge between Filament's theme switcher and Flux's appearance store, so toggling theme in either system stays in sync (including cross-tab via `storage` events).

## Form Fields

```php
use Jeffersongoncalves\FilamentFlux\Forms\Components\{FluxInput, FluxTextarea, FluxSelect, FluxCheckbox, FluxCheckboxGroup, FluxRadio, FluxRadioGroup, FluxSwitch};

FluxInput::make('email')
    ->email()
    ->fluxIcon('envelope')
    ->fluxClearable()
    ->fluxCopyable()
    ->required();

FluxTextarea::make('bio')->rows(8)->resize('vertical');

FluxSelect::make('status')
    ->options(['draft' => 'Draft', 'published' => 'Published'])
    ->required();

FluxCheckbox::make('terms')->required();

FluxCheckboxGroup::make('tags')
    ->fluxVariant('cards')                // default | cards | pills | buttons
    ->options(Tag::pluck('name', 'id'));

FluxRadioGroup::make('plan')
    ->fluxVariant('segmented')            // default | segmented | cards
    ->options(['free' => 'Free', 'pro' => 'Pro']);

FluxSwitch::make('notifications')->fluxAlign('right');
```

## Actions

```php
use Jeffersongoncalves\FilamentFlux\Actions\{FluxAction, FluxDropdown};

FluxAction::make('publish')
    ->fluxVariant('primary')              // outline | filled | primary | danger | ghost | subtle
    ->fluxIcon('rocket-launch')
    ->fluxKbd('cmd+enter')
    ->fluxLoading()                       // bool or wire:target string
    ->fluxTooltip('Publish now')
    ->requiresConfirmation()              // delegates to Filament's native modal
    ->action(fn (Post $record) => $record->publish());

FluxDropdown::make([
    FluxAction::make('edit'),
    FluxAction::make('archive'),
    FluxAction::make('delete'),
])
    ->label('Actions')
    ->fluxIcon('ellipsis-horizontal');
```

> **Note on modals:** Action confirmation/forms reuse Filament's native modal system (consistent with the rest of the panel). `<flux:modal>` is not available in Flux free; use Filament modals or roll your own with Alpine in Custom Pages.

## Table Columns

```php
use Jeffersongoncalves\FilamentFlux\Tables\Columns\{FluxBadgeColumn, FluxAvatarColumn, FluxIconColumn};

FluxBadgeColumn::make('status')
    ->fluxColor([
        'draft' => 'zinc',
        'published' => 'lime',
        'archived' => 'red',
    ])
    ->fluxIcon(fn ($state) => $state === 'published' ? 'check-circle' : null)
    ->fluxBadgeVariant('pill');

FluxAvatarColumn::make('user.avatar')
    ->fluxName(fn ($state, $record) => $record->user->name)
    ->fluxColor(fn ($state, $record) => $record->user->is_admin ? 'blue' : 'zinc')
    ->fluxSize('sm')
    ->fluxBadge('green');                 // status dot

FluxIconColumn::make('priority')
    ->fluxColor(fn ($state) => $state === 'high' ? 'red' : 'zinc')
    ->fluxIconVariant('solid');
```

## Infolist Entries

```php
use Jeffersongoncalves\FilamentFlux\Infolists\Components\{FluxBadgeEntry, FluxAvatarEntry, FluxIconEntry, FluxTextEntry};

FluxBadgeEntry::make('role')
    ->fluxColor('blue')
    ->fluxIcon('shield-check');

FluxAvatarEntry::make('avatar')
    ->fluxName(fn ($state, $record) => $record->name)
    ->fluxSize('lg');

FluxIconEntry::make('priority')
    ->fluxIconVariant('solid')
    ->fluxColor('red');

FluxTextEntry::make('description')
    ->fluxSize('lg')
    ->fluxColor('zinc');
```

## Color resolver

`fluxColor()` accepts three forms:

```php
->fluxColor('lime')                                  // fixed
->fluxColor(['draft' => 'zinc', 'done' => 'lime'])   // state map
->fluxColor(fn ($state, $record) => 'blue')          // closure
```

Valid Flux colors: `zinc`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`.

## FluxIcon helper

```php
use Jeffersongoncalves\FilamentFlux\Support\FluxIcon;

// Inline rendering inside Blade or Closures.
echo FluxIcon::make('star')->fluxVariant('outline')->class('size-6');
```

## OTP input

```php
use Jeffersongoncalves\FilamentFlux\Forms\Components\FluxOtpInput;

FluxOtpInput::make('code')
    ->length(6)
    ->private()                  // mask the digits
    ->required();
```

## Link Action / Column / Entry

```php
use Jeffersongoncalves\FilamentFlux\Actions\FluxLinkAction;
use Jeffersongoncalves\FilamentFlux\Tables\Columns\FluxLinkColumn;
use Jeffersongoncalves\FilamentFlux\Infolists\Components\FluxLinkEntry;

FluxLinkAction::make('docs')
    ->url('https://example.com/docs')
    ->external()
    ->fluxIcon('book-open');

FluxLinkColumn::make('homepage')
    ->href(fn ($state, $record) => $record->homepage_url)
    ->external()
    ->fluxVariant('subtle');

FluxLinkEntry::make('homepage')
    ->href(fn ($state, $record) => $record->homepage_url)
    ->external();
```

## Schema components

Drop-in for forms and infolists.

```php
use Jeffersongoncalves\FilamentFlux\Schemas\Components\{
    FluxHeading,
    FluxSubheading,
    FluxSeparator,
    FluxSpacer,
    FluxCallout,
    FluxFieldset,
    FluxCard,
    FluxSkeleton,
    FluxProgress,
};

FluxHeading::make('Account')->level('2')->size('xl');
FluxSubheading::make('Profile details')->size('sm');
FluxSeparator::make()->orientation('horizontal')->variant('subtle')->text('OR');
FluxSpacer::make();

FluxCallout::make()
    ->variant('warning')                // success | danger | warning | secondary
    ->fluxIcon('exclamation-triangle')
    ->heading('Heads up')
    ->text('Action required.');

FluxFieldset::make('Personal info')->schema([
    FluxInput::make('first_name'),
    FluxInput::make('last_name'),
]);

FluxCard::make()->schema([
    FluxInput::make('email'),
]);

FluxSkeleton::make()->class('h-10 w-full');

FluxProgress::make()->value(72)->fluxColor('lime');
```

## Modal helper for Custom Pages

```blade
<x-flux::modal name="confirm-delete" variant="floating" position="right">
    <x-flux::heading>Confirm deletion</x-flux::heading>
    <x-flux::text>This action cannot be undone.</x-flux::text>
</x-flux::modal>

<x-flux::modal.trigger name="confirm-delete">
    <x-flux::button variant="danger">Delete</x-flux::button>
</x-flux::modal.trigger>
```

Programmatic open/close from Livewire:

```php
use Jeffersongoncalves\FilamentFlux\Components\FluxModal;

$this->dispatch(...array_values(FluxModal::openEvent('confirm-delete')));
```

> Action confirmation modals reuse Filament's native modal system (`->requiresConfirmation()`). Use `<flux:modal>` only in Custom Pages where you control the markup directly.

## Toast notifications

```php
use Jeffersongoncalves\FilamentFlux\Components\FluxToast;

$this->dispatch(...FluxToast::success('Saved!')->heading('Done')->dispatchArgs());
```

## Breadcrumbs and pagination

```php
use Jeffersongoncalves\FilamentFlux\Components\FluxBreadcrumbs;
use Jeffersongoncalves\FilamentFlux\Components\FluxPagination;

{!! FluxBreadcrumbs::make()
    ->add('Home', '/')
    ->add('Posts', '/posts', 'document-text')
    ->add('Edit') !!}

{!! FluxPagination::make($posts) !!}
```

## Custom Pages — direct Blade usage

Components that don't have a Filament wrapper (page-level layout, not Form/Schema primitives) are still available via Flux's anonymous Blade components in any Custom Page:

`<x-flux::accent>`, `<x-flux::aside>`, `<x-flux::brand>`, `<x-flux::container>`, `<x-flux::main>`, `<x-flux::header>`, `<x-flux::footer>`, `<x-flux::navbar>`, `<x-flux::navlist>`, `<x-flux::navmenu>`, `<x-flux::sidebar>`, `<x-flux::profile>`, `<x-flux::label>`, `<x-flux::legend>`, `<x-flux::error>`, `<x-flux::field>`, `<x-flux::description>`, `<x-flux::link>`, `<x-flux::heading>`, `<x-flux::subheading>`, `<x-flux::text>`, `<x-flux::tooltip>`, `<x-flux::with-field>`, `<x-flux::with-tooltip>`.

## Theme bridge

`dist/filament-flux.css` maps Filament's design tokens (`--fi-color-primary-*`, `--fi-color-gray-*`) to Flux's tokens (`--color-accent`, `--color-zinc-*`) inside `.filament-flux-scope`. Disable the wrapper to keep Flux's native zinc palette:

```php
FilamentFluxPlugin::make()->scopeClass(null);
```

## Troubleshooting

**Components render unstyled (look like raw HTML).**
Tailwind v4 isn't scanning Flux's source. Re-run `php artisan filament-flux:install --panel=admin` and `npm run build`. Confirm the two `@import` lines are present in `resources/css/filament/{panel}/theme.css`.

**Theme toggle doesn't propagate to Flux components.**
The plugin injects a bridge script. If you disabled `injectAppearance(false)`, you also disabled the bridge. Re-enable it or wire your own listener for `theme-changed`.

**`auth.json` warning during install.**
Only relevant for `filament-flux-pro` (sister package). Ignore for `filament-flux`.

**Validation error appears twice.**
Filament's wrapper renders the error message; the Flux component renders a red border via `:invalid`. They are not duplicates — the border is visual only.

## Testing

```bash
composer test
composer analyse
composer format
```

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)

## License

The MIT License (MIT). See [License File](LICENSE.md).
