# Fase 3 — Actions, Modal, Dropdown, Tooltip

## Contexto

Fase 3 de 5 do `jeffersongoncalves/filament-flux`. Pré-requisitos: Fases 1 e 2 entregues.

Objetivo: expor `flux:button`, `flux:dropdown`, `flux:menu`, `flux:modal` e `flux:tooltip` como Actions Filament e helpers de UI.

NÃO escopo: Tables/Infolists (Fase 4).

## Componentes

| Classe | Flux render | Função |
|---|---|---|
| `FluxAction extends Action` | `<flux:button>` | Action customizada substituindo botão Filament padrão |
| `FluxDropdown` | `<flux:dropdown> + <flux:menu>` | Helper para action groups |
| `FluxModal` | `<flux:modal>` | Helper para Custom Pages (NÃO usar em Action modals) |
| Trait `HasFluxTooltip` | adiciona `<flux:tooltip>` em qualquer Field/Action | mixin |
| Helper `FluxIcon` | `<flux:icon>` | Wrapper de heroicons |

## Estrutura

```
src/Actions/
├── FluxAction.php
├── FluxDropdown.php
└── Concerns/
    ├── HasFluxVariant.php   // 'primary' | 'filled' | 'outline' | 'ghost' | 'danger' | 'subtle'
    ├── HasFluxLoading.php
    └── HasFluxKbd.php

src/Components/
└── FluxModal.php             // helper estático para Custom Pages

src/Concerns/
└── HasFluxTooltip.php

src/Support/
└── FluxIcon.php              // ::make('star')->fluxVariant('outline')

resources/views/components/
├── action.blade.php
├── dropdown.blade.php
└── modal.blade.php
```

## `FluxAction`

Extends `Filament\Actions\Action`:

```php
namespace Jeffersongoncalves\FilamentFlux\Actions;

use Filament\Actions\Action;
use Jeffersongoncalves\FilamentFlux\Actions\Concerns\HasFluxVariant;
use Jeffersongoncalves\FilamentFlux\Actions\Concerns\HasFluxLoading;
use Jeffersongoncalves\FilamentFlux\Actions\Concerns\HasFluxKbd;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxTooltip;

class FluxAction extends Action
{
    use HasFluxVariant;
    use HasFluxLoading;
    use HasFluxKbd;
    use HasFluxIcon;
    use HasFluxTooltip;

    protected string $view = 'filament-flux::components.action';
}
```

DSL:

```php
FluxAction::make('publish')
    ->fluxVariant('primary')
    ->fluxIcon('rocket-launch')
    ->fluxLoading()
    ->fluxKbd('cmd+enter')
    ->fluxTooltip('Publicar agora')
    ->requiresConfirmation()             // delega a modal Filament padrão
    ->action(fn ($record) => $record->publish());
```

### Blade

```blade
{{-- resources/views/components/action.blade.php --}}
<flux:button
    :variant="$getFluxVariant()"
    :icon="$getFluxIcon()"
    :icon:trailing="$getFluxIconTrailing()"
    :loading="$shouldFluxLoading() ? $getStatePath() : null"
    :kbd="$getFluxKbd()"
    :size="$getFluxSize()"
    :type="$getType()"
    :disabled="$isDisabled()"
    :href="$getUrl()"
    :target="$shouldOpenUrlInNewTab() ? '_blank' : null"
    {{ $getExtraAttributesBag() }}
    {{ $getActionLivewireAttributes() }}
>
    {{ $getLabel() }}
</flux:button>

@if($getFluxTooltip())
    <flux:tooltip>{{ $getFluxTooltip() }}</flux:tooltip>
@endif
```

`$getActionLivewireAttributes()` deve retornar `wire:click` ou similar. Investigar API exata do `Filament\Actions\Action` v5 para extrair callable invocation.

## Modal — decisão de design

Filament Action modals (`requiresConfirmation()`, `modalContent()`) já usam o sistema de modal nativo do Filament (overlay próprio). Misturar `<flux:modal>` aqui = dupla overlay.

**Regra:**

- `FluxAction::make()->requiresConfirmation()` → usa modal Filament nativo (consistência com resto do painel).
- `FluxModal` é helper APENAS para Custom Pages onde dev controla markup direto.
- Documentar no DocBlock e README.

### `FluxModal` (Custom Pages only)

```php
namespace Jeffersongoncalves\FilamentFlux\Components;

class FluxModal
{
    public static function open(string $name, array $variables = []): string
    {
        // Retorna HTML para abrir modal via Alpine
    }
}
```

Blade direto também documentado:

```blade
<flux:modal name="confirm-delete" class="max-w-md">
    <flux:heading>Confirmar exclusão?</flux:heading>
    {{-- ... --}}
</flux:modal>

<flux:modal.trigger name="confirm-delete">
    <flux:button variant="danger">Excluir</flux:button>
</flux:modal.trigger>
```

## `FluxDropdown` — Action Group

```php
namespace Jeffersongoncalves\FilamentFlux\Actions;

use Filament\Actions\ActionGroup;

class FluxDropdown extends ActionGroup
{
    protected string $view = 'filament-flux::components.dropdown';

    public static function make(array $actions): static
    {
        return parent::make($actions);
    }
}
```

```blade
{{-- resources/views/components/dropdown.blade.php --}}
<flux:dropdown>
    <flux:button :icon:trailing="'chevron-down'">{{ $getLabel() }}</flux:button>
    <flux:menu>
        @foreach($getActions() as $action)
            <flux:menu.item
                :icon="$action->getFluxIcon() ?? $action->getIcon()"
                wire:click="mountAction('{{ $action->getName() }}')"
            >
                {{ $action->getLabel() }}
            </flux:menu.item>
        @endforeach
    </flux:menu>
</flux:dropdown>
```

## Trait `HasFluxTooltip`

```php
namespace Jeffersongoncalves\FilamentFlux\Concerns;

trait HasFluxTooltip
{
    protected string | \Closure | null $fluxTooltip = null;
    protected string $fluxTooltipPosition = 'top';

    public function fluxTooltip(string | \Closure | null $tooltip, string $position = 'top'): static
    {
        $this->fluxTooltip = $tooltip;
        $this->fluxTooltipPosition = $position;
        return $this;
    }

    public function getFluxTooltip(): ?string
    {
        return $this->evaluate($this->fluxTooltip);
    }

    public function getFluxTooltipPosition(): string
    {
        return $this->fluxTooltipPosition;
    }
}
```

Aplicável em `FluxAction`, `FluxInput`, `FluxSelect` etc.

## `FluxIcon` helper

```php
namespace Jeffersongoncalves\FilamentFlux\Support;

class FluxIcon
{
    public static function make(string $name): self { /* ... */ }
    public function fluxVariant(string $variant): self { /* outline | solid | mini | micro */ }
    public function class(string $class): self { /* ... */ }
    public function toHtml(): string { /* renderiza <flux:icon> */ }
}
```

Útil em closures de TextColumn, ViewColumn etc.

## Testes Pest

`tests/Feature/Actions/FluxActionTest.php`:

- Render correto com variant
- `fluxLoading()` injeta `loading="statePath"` no markup
- `fluxKbd('cmd+enter')` aparece no markup
- `requiresConfirmation()` → modal Filament padrão (NÃO `<flux:modal>`)
- Dispara handler via Livewire test

`tests/Feature/Actions/FluxDropdownTest.php`:

- Renderiza `<flux:dropdown>` + `<flux:menu>` com itens
- Clicar item dispara action (via `livewire(...)->call('mountAction', 'name')`)

`tests/Feature/Components/FluxTooltipTest.php`:

- Trait aplicada em `FluxAction` adiciona `<flux:tooltip>`
- Position configurável

`tests/Unit/FluxIconTest.php`:

- Helper renderiza tag correta
- Variants e class extra funcionam

## Critérios de aceite

- [ ] `FluxAction` renderiza `<flux:button>` com todos os props
- [ ] `FluxAction::make()->fluxLoading()` ativa loading state nativo do Flux
- [ ] `requiresConfirmation()` mostra modal Filament (não Flux modal)
- [ ] `FluxDropdown` agrupa actions corretamente
- [ ] `FluxIcon::make('star')` renderiza inline
- [ ] Trait `HasFluxTooltip` aplicável e funcional
- [ ] PHPStan + Pint + Pest verdes
- [ ] Smoke test: abrir Custom Page com `FluxAction` + `FluxDropdown` + `FluxModal` em Blade direto

## Arquivos de saída

```
src/Actions/FluxAction.php
src/Actions/FluxDropdown.php
src/Actions/Concerns/{3 traits}.php
src/Components/FluxModal.php
src/Concerns/HasFluxTooltip.php
src/Support/FluxIcon.php
resources/views/components/{action,dropdown,modal}.blade.php
tests/Feature/Actions/{2 tests}.php
tests/Feature/Components/FluxTooltipTest.php
tests/Unit/FluxIconTest.php
```
