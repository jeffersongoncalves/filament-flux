# Fase 4 — Infolist Entries + Table Columns

## Contexto

Fase 4 de 5 do `jeffersongoncalves/filament-flux`. Pré-requisitos: Fases 1, 2, 3 entregues.

Objetivo: expor `flux:badge`, `flux:avatar`, `flux:icon`, `flux:text` como Infolist Entries e Table Columns.

NÃO escopo: polimento/docs (Fase 5).

## Componentes

### Infolist Entries (`src/Infolists/Components/`)

| Classe | Flux render | DSL |
|---|---|---|
| `FluxBadgeEntry extends Entry` | `<flux:badge>` | `fluxColor(Closure\|string\|array)`, `fluxIcon()`, `fluxVariant()` (`'solid' \| 'pill'`), `fluxSize()` |
| `FluxAvatarEntry extends Entry` | `<flux:avatar>` | `fluxSrc()`, `fluxName()`, `fluxColor()`, `fluxSize()`, `fluxBadge()` (status dot) |
| `FluxIconEntry extends Entry` | `<flux:icon>` | `fluxIconVariant()` (`'outline' \| 'solid'`), `fluxColor()` |
| `FluxTextEntry extends Entry` | `<flux:text>` | `fluxSize()`, `fluxColor()` |

### Table Columns (`src/Tables/Columns/`)

| Classe | Flux render | DSL |
|---|---|---|
| `FluxBadgeColumn extends Column` | `<flux:badge>` | `fluxColor()`, `fluxIcon()`, `fluxVariant()`, `fluxSize()` |
| `FluxAvatarColumn extends Column` | `<flux:avatar>` | `fluxSrc()`, `fluxName()`, `fluxColor()`, `fluxSize()`, `fluxBadge()` |
| `FluxIconColumn extends Column` | `<flux:icon>` | `fluxIconVariant()`, `fluxColor()` |

## Color resolver

DSL aceita 3 formas:

```php
// 1. String fixa
->fluxColor('lime')

// 2. Closure recebendo state
->fluxColor(fn (string $state) => match ($state) {
    'draft' => 'zinc',
    'published' => 'lime',
    'archived' => 'red',
})

// 3. Array map state → color
->fluxColor([
    'draft' => 'zinc',
    'published' => 'lime',
    'archived' => 'red',
])
```

Trait reutilizável `Concerns/HasFluxColor`:

```php
namespace Jeffersongoncalves\FilamentFlux\Concerns;

trait HasFluxColor
{
    protected string | \Closure | array | null $fluxColor = null;

    public function fluxColor(string | \Closure | array | null $color): static
    {
        $this->fluxColor = $color;
        return $this;
    }

    public function getFluxColor(mixed $state = null): ?string
    {
        if (is_array($this->fluxColor)) {
            return $this->fluxColor[$state] ?? null;
        }
        return $this->evaluate($this->fluxColor, ['state' => $state]);
    }
}
```

Cores Flux válidas: `zinc`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`. Validar opcionalmente.

## Estrutura

```
src/Infolists/Components/
├── FluxBadgeEntry.php
├── FluxAvatarEntry.php
├── FluxIconEntry.php
└── FluxTextEntry.php

src/Tables/Columns/
├── FluxBadgeColumn.php
├── FluxAvatarColumn.php
└── FluxIconColumn.php

src/Concerns/
├── HasFluxColor.php
├── HasFluxAvatar.php       // src, name, badge (status dot)
└── HasFluxBadgeStyle.php   // variant + size

resources/views/components/
├── infolist/
│   ├── badge.blade.php
│   ├── avatar.blade.php
│   ├── icon.blade.php
│   └── text.blade.php
└── table/
    ├── badge-column.blade.php
    ├── avatar-column.blade.php
    └── icon-column.blade.php
```

## Implementação — exemplo `FluxBadgeColumn`

```php
namespace Jeffersongoncalves\FilamentFlux\Tables\Columns;

use Filament\Tables\Columns\Column;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxColor;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxBadgeStyle;

class FluxBadgeColumn extends Column
{
    use HasFluxColor;
    use HasFluxIcon;
    use HasFluxBadgeStyle;

    protected string $view = 'filament-flux::components.table.badge-column';
}
```

```blade
{{-- table/badge-column.blade.php --}}
@php
    $state = $getState();
    $color = $getFluxColor($state);
@endphp

<flux:badge
    :color="$color"
    :icon="$getFluxIcon()"
    :variant="$getFluxVariant()"
    :size="$getFluxSize()"
>
    {{ $getFormattedState() ?? $state }}
</flux:badge>
```

## DSL exemplos (cobrir nos testes)

```php
// Tabela
FluxBadgeColumn::make('status')
    ->fluxColor([
        'draft' => 'zinc',
        'published' => 'lime',
        'archived' => 'red',
    ])
    ->fluxIcon(fn (string $state) => match ($state) {
        'published' => 'check-circle',
        'archived' => 'archive-box',
        default => null,
    })
    ->fluxVariant('pill');

FluxAvatarColumn::make('user.avatar')
    ->fluxName(fn ($record) => $record->user->name)
    ->fluxColor(fn ($record) => $record->user->is_admin ? 'blue' : 'zinc')
    ->fluxSize('sm')
    ->fluxBadge('green');     // status dot

FluxIconColumn::make('priority')
    ->fluxColor(fn ($state) => $state === 'high' ? 'red' : 'zinc')
    ->fluxIconVariant('solid');

// Infolist
FluxBadgeEntry::make('role')
    ->fluxColor('blue')
    ->fluxIcon('shield-check');

FluxTextEntry::make('description')
    ->fluxSize('lg')
    ->fluxColor('zinc');
```

## Testes Pest

`tests/Feature/Tables/{Name}ColumnTest.php`:

- Render com state simulado
- Color resolver: string fixa, closure, array map
- Icon resolver
- Variant/size aplicados

`tests/Feature/Infolists/{Name}EntryTest.php`:

- Render no contexto de Infolist
- DSL methods funcionam

`tests/Unit/HasFluxColorTest.php`:

- 3 formas de color resolver retornam valor correto
- Estado nulo → cor nula

Fixtures: criar `tests/Fixtures/PostResource.php` com Resource minimalista usando os componentes; rodar via `livewire(ListPosts::class)->assertSeeHtml('flux:badge')`.

## Critérios de aceite

- [ ] 4 Infolist Entries renderizando Flux components
- [ ] 3 Table Columns renderizando Flux components
- [ ] Color resolver suporta string/closure/array
- [ ] Icon resolver via closure funciona
- [ ] PHPStan + Pint + Pest verdes
- [ ] Smoke test: Resource demo lista 5 registros com badges, avatares e icons coloridos

## Arquivos de saída

```
src/Infolists/Components/{4 classes}.php
src/Tables/Columns/{3 classes}.php
src/Concerns/{HasFluxColor,HasFluxAvatar,HasFluxBadgeStyle}.php
resources/views/components/infolist/{4 views}.blade.php
resources/views/components/table/{3 views}.blade.php
tests/Feature/Infolists/{4 tests}.php
tests/Feature/Tables/{3 tests}.php
tests/Unit/HasFluxColorTest.php
tests/Fixtures/PostResource.php
```
