# Fase 2 — Form Fields essenciais

## Contexto

Fase 2 de 5 do `jeffersongoncalves/filament-flux`. Pré-requisito: Fase 1 entregue (skeleton + plugin + render hooks).

Objetivo: expor os Form Fields free do Flux como `Filament\Forms\Components\Field`, com state binding para `$statePath`, validação, e DSL fluente alinhada com Filament idiomático.

NÃO escopo: Actions, Modal, Dropdown (Fase 3); Tables/Infolists (Fase 4).

## Componentes a entregar

| Classe Filament | Flux render | DSL específica |
|---|---|---|
| `FluxInput extends Field` | `<flux:input>` | `type()`, `fluxIcon()`, `fluxIconTrailing()`, `fluxClearable()`, `fluxCopyable()`, `fluxKbd()` |
| `FluxTextarea extends Field` | `<flux:textarea>` | `rows()`, `resizable()` |
| `FluxSelect extends Field` | `<flux:select>` | `fluxVariant()` (`'native' \| 'listbox' \| 'combobox'`), `options()`, `fluxSearchable()`, `fluxMultiple()`, `fluxClearable()` |
| `FluxCheckbox extends Field` | `<flux:checkbox>` | herda label/description |
| `FluxCheckboxGroup extends Field` | `<flux:checkbox.group>` | `options()`, `fluxVariant()` (`'default' \| 'cards' \| 'pills'`) |
| `FluxRadio extends Field` | `<flux:radio>` | (raramente usado solo; mantido para paridade) |
| `FluxRadioGroup extends Field` | `<flux:radio.group>` | `options()`, `fluxVariant()` (`'default' \| 'segmented' \| 'cards'`) |
| `FluxSwitch extends Field` | `<flux:switch>` | `fluxAlign()` (`'left' \| 'right'`) |

## Estrutura

```
src/Forms/Components/
├── FluxInput.php
├── FluxTextarea.php
├── FluxSelect.php
├── FluxCheckbox.php
├── FluxCheckboxGroup.php
├── FluxRadio.php
├── FluxRadioGroup.php
└── FluxSwitch.php

src/Concerns/
├── HasFluxIcon.php          // ->fluxIcon(string), ->fluxIconTrailing(string)
├── HasFluxVariant.php       // ->fluxVariant(string)
├── HasFluxClearable.php     // ->fluxClearable(bool|Closure)
├── HasFluxCopyable.php
├── HasFluxKbd.php
└── HasFluxSize.php          // 'xs' | 'sm' | 'base' | 'lg'

resources/views/components/form/
├── input.blade.php
├── textarea.blade.php
├── select.blade.php
├── checkbox.blade.php
├── checkbox-group.blade.php
├── radio.blade.php
├── radio-group.blade.php
└── switch.blade.php
```

## Padrão de implementação (template)

### Classe PHP

```php
namespace Jeffersongoncalves\FilamentFlux\Forms\Components;

use Filament\Forms\Components\Field;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxIcon;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxClearable;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxCopyable;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxKbd;
use Jeffersongoncalves\FilamentFlux\Concerns\HasFluxSize;

class FluxInput extends Field
{
    use HasFluxIcon;
    use HasFluxClearable;
    use HasFluxCopyable;
    use HasFluxKbd;
    use HasFluxSize;

    protected string $view = 'filament-flux::components.form.input';

    protected string | \Closure $type = 'text';

    public function type(string | \Closure $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->evaluate($this->type);
    }
}
```

### Blade view (template)

```blade
{{-- resources/views/components/form/input.blade.php --}}
@php
    $statePath = $getStatePath();
    $debounce = config('filament-flux.live_debounce', 500);
    $live = $isLive() || $isLiveOnBlur() || $isLiveDebounced();
    $wireModel = $live
        ? 'wire:model.live' . ($isLiveDebounced() ? ".debounce.{$debounce}ms" : ($isLiveOnBlur() ? '.blur' : ''))
        : 'wire:model';
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <flux:input
        {{ $wireModel }}="{{ $statePath }}"
        :type="$getType()"
        :placeholder="$getPlaceholder()"
        :icon="$getFluxIcon()"
        :icon:trailing="$getFluxIconTrailing()"
        :clearable="$isFluxClearable()"
        :copyable="$isFluxCopyable()"
        :kbd="$getFluxKbd()"
        :size="$getFluxSize()"
        :required="$isRequired()"
        :disabled="$isDisabled()"
        :readonly="$isReadOnly()"
        :invalid="$errors->has($statePath)"
    />
</x-dynamic-component>
```

Repetir o padrão para os outros componentes.

## State binding — wire:model

Filament usa `getStatePath()` retornando algo como `data.email`. Flux/Livewire entendem dot notation. Bridge:

- Default: `wire:model="data.email"`
- Live: `wire:model.live="data.email"`
- Live debounced: `wire:model.live.debounce.500ms="data.email"` (debounce vem de `config('filament-flux.live_debounce')`)
- Live onBlur: `wire:model.live.blur="data.email"`

Usar `$component->isLive()`, `isLiveOnBlur()`, `isLiveDebounced()` do Filament.

## Validação

`<flux:field>` envolve label+input+description+error. Como o Filament já tem seu wrapper (`x-dynamic-component :component="$getFieldWrapperView()"`), evitar duplicar. Estratégia:

- Filament wrapper renderiza label, helper text, e error.
- Blade interno renderiza só `<flux:input>` etc. com `:invalid="$errors->has($statePath)"` para borda vermelha do Flux.

Confirmar visualmente que erro mostrado pelo Filament + borda vermelha do Flux fica coerente. Se duplicar visualmente, ajustar para usar `<flux:field>` completo dentro e DESABILITAR wrapper Filament via `hasInlineLabel()` ou view própria.

## DSL exemplos (cobrir nos testes)

```php
FluxInput::make('email')
    ->type('email')
    ->fluxIcon('envelope')
    ->fluxClearable()
    ->fluxCopyable()
    ->placeholder('seu@email.com')
    ->required()
    ->live(onBlur: true);

FluxSelect::make('status')
    ->fluxVariant('listbox')
    ->fluxSearchable()
    ->fluxMultiple()
    ->options([
        'draft' => 'Rascunho',
        'published' => 'Publicado',
    ])
    ->default('draft');

FluxCheckboxGroup::make('tags')
    ->fluxVariant('cards')
    ->options(Tag::pluck('name', 'id'));

FluxRadioGroup::make('plan')
    ->fluxVariant('segmented')
    ->options(['free' => 'Free', 'pro' => 'Pro']);

FluxSwitch::make('notifications')
    ->fluxAlign('right');
```

## Testes Pest

Para cada componente, criar `tests/Feature/Forms/{Name}Test.php` cobrindo:

- Render: classe renderiza view correta com state path bound
- State binding: `livewire(TestForm::class)->fillForm([...])->assertHasNoFormErrors()`
- Validação: `->fillForm(['email' => 'inválido'])->call('save')->assertHasFormErrors(['email' => 'email'])`
- DSL methods: `FluxInput::make('x')->fluxClearable()->isFluxClearable()` retorna `true`

`tests/Fixtures/TestForm.php` — Livewire component minimalista usando Filament Form trait, com array `data` público.

## Integração com Filament wrappers existentes

Verificar se `Filament\Forms\Components\Field` em v5 tem:
- `getStatePath(): string`
- `isLive(): bool`, `isLiveOnBlur()`, `isLiveDebounced()`
- `getPlaceholder()`, `isRequired()`, `isDisabled()`, `isReadOnly()`
- `getFieldWrapperView()`

Se algum método mudou nome em v5, ajustar e documentar no commit.

## Critérios de aceite

- [ ] 8 classes Form criadas com PSR-4 correto
- [ ] 6 traits em `Concerns/` reutilizáveis
- [ ] 8 views Blade em `resources/views/components/form/`
- [ ] PHPStan level 5 limpo
- [ ] Pint sem diffs
- [ ] Pest verde para todos os componentes (render + state + validation)
- [ ] DSL exemplos da seção acima funcionam num smoke test manual
- [ ] Erros de validação aparecem visualmente uma vez (sem duplicação)
- [ ] `wire:model.live.debounce` respeita config

## Arquivos de saída

```
src/Forms/Components/{8 classes}.php
src/Concerns/{6 traits}.php
resources/views/components/form/{8 views}.blade.php
tests/Feature/Forms/{8 tests}.php
tests/Fixtures/TestForm.php
```
