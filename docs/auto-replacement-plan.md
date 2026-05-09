# Plano: substituir classes nativas do Filament pelas variantes Flux automaticamente

> Status: planejamento (não implementado). Documento gerado a partir da inspeção
> do core do Filament v5.6.2 instalado em `vendor/filament/*`.

## 1. O que torna isso possível

Toda classe-base do Filament (`Field`, `Column`, `Entry`, `Action`, `ActionGroup`)
constrói instâncias via container do Laravel:

| Classe-base | Localização | Construtor |
|---|---|---|
| `Filament\Forms\Components\Field` | `vendor/filament/forms/src/Components/Field.php:70` | `app($fieldClass, ['name' => $name])` |
| `Filament\Tables\Columns\Column` | `vendor/filament/tables/src/Columns/Column.php:79` | `app($columnClass, ['name' => $name])` |
| `Filament\Infolists\Components\Entry` | `vendor/filament/infolists/src/Components/Entry.php:72` | `app($entryClass, ['name' => $name])` |
| `Filament\Actions\Action` | `vendor/filament/actions/src/Action.php:138` | `app(static::class, [...])` |

`$fieldClass = static::class`. Significa que `TextInput::make()` chama
`app(TextInput::class)`. Se registrarmos `TextInput::class => FluxInput::class`
no container, `TextInput::make('email')` devolve uma instância de **FluxInput**
(que estende TextInput, portanto continua válido para typehints `TextInput`).

Como `FluxInput` apenas troca o `$view` e mistura traits Flux, a ergonomia do
Filament permanece intacta — `->autocomplete('email')`, `->revealable()`,
`->mask(...)`, validação, hidratação de relacionamentos, etc. — só a renderização
muda para `<flux:input>`.

## 2. Mapeamento (Filament → filament-flux)

### 2.1. Form Fields

| Filament | filament-flux | Substituição automática? |
|---|---|---|
| `TextInput` | `FluxInput` | ✅ Sim |
| `Textarea` | `FluxTextarea` | ✅ Sim |
| `Select` (native) | `FluxSelect` | ⚠️ Sim, mas limitado: nossa `FluxSelect` extende `Select` e mantém a feature surface (search/preload/relationships funcionam server-side) — porém a UI passa a ser um `<select>` nativo dentro de `<flux:select>`. Searchable client-side cai. |
| `Checkbox` | `FluxCheckbox` | ✅ Sim |
| `CheckboxList` | `FluxCheckboxGroup` | ✅ Sim |
| `Radio` | `FluxRadio` | ✅ Sim |
| `Toggle` | `FluxSwitch` | ✅ Sim |
| `OneTimeCodeInput` | `FluxOtpInput` | ⚠️ Sim, mas API divergente: `digits()` em vez do `length()` herdado. Sob auto-bind, o `FluxOtpInput` continua aceitando o que o `OneTimeCodeInput` expõe. |
| `DatePicker` / `TimePicker` / `DateTimePicker` | `<flux:date-picker>` é Pro | ❌ Apenas no `filament-flux-pro` |
| `ColorPicker` | `<flux:color-picker>` é Pro | ❌ Apenas no Pro |
| `RichEditor` | `<flux:editor>` é Pro | ❌ Apenas no Pro |
| `MarkdownEditor` | sem equivalente Flux | ❌ Mantém TipTap |
| `FileUpload` | `<flux:file-upload>` é Pro | ❌ Apenas no Pro |
| `TagsInput` | `<flux:pillbox>` é Pro | ❌ Apenas no Pro |
| `KeyValue`, `Repeater`, `Builder`, `Slider`, `CodeEditor`, `MorphToSelect`, `MultiSelect`, `TableSelect`, `ToggleButtons`, `Hidden`, `Placeholder`, `LivewireField`, `ViewField` | sem equivalente Flux direto | ❌ Mantém Filament |

### 2.2. Table Columns

Substituição automática **não é viável** porque a semântica diverge:

| Filament | filament-flux | Por quê não auto |
|---|---|---|
| `TextColumn` | múltiplos (`FluxBadgeColumn`, `FluxLinkColumn`) | Mesma classe-base, intenções diferentes (badge ≠ texto). |
| `BadgeColumn` (deprecated em v5; usa `TextColumn::badge()`) | `FluxBadgeColumn` | Filament v5 já não tem classe `BadgeColumn` separada; `->badge()` é modifier. |
| `IconColumn` | `FluxIconColumn` | ✅ Poderia auto-bind, mas IconColumn já renderiza ícone via Heroicons; ganho é só o tema accent. Opt-in vale mais. |
| `ImageColumn` | `FluxAvatarColumn` | Avatar tem semântica adicional (initials, status badge). Auto-bind quebraria casos genéricos. |
| `BooleanColumn`, `CheckboxColumn`, `ColorColumn`, `SelectColumn`, `TagsColumn`, `TextInputColumn`, `ToggleColumn`, `ViewColumn` | sem wrapper | Manter Filament. |

**Decisão:** colunas ficam **opt-in** (`FluxBadgeColumn::make(...)` explícito).

### 2.3. Infolist Entries

Mesmo critério das colunas. Auto-bind sai. Opt-in fica.

### 2.4. Actions

| Filament | filament-flux | Auto? |
|---|---|---|
| `Filament\Actions\Action` | `FluxAction` | ⚠️ Possível, mas **arriscado** |
| `EditAction`, `DeleteAction`, `CreateAction`, `ViewAction`, `BulkAction`, `BulkActionGroup`, etc. | — | Cada um é uma subclasse de `Action`. `EditAction::make()` faz `app(EditAction::class)` (não passa pelo binding de `Action::class`). |

Risco do auto-bind em `Action::class`:
- Captura **apenas** `Action::make('foo')` cru.
- Subclasses Filament continuam Filament.
- Custom actions definidas pelo dev como `Action::make(...)` viram `FluxAction`.
- Visualmente: bridge CSS já alinha `<x-filament::button>` (Filament padrão) e `<x-flux::button>` (Flux) ao mesmo accent. Ganho de auto-bind é pequeno.

**Decisão:** actions ficam **opt-in**.

## 3. Estratégias avaliadas

### 3.A. Container binding via plugin flag (preferida)

```php
FilamentFluxPlugin::make()
    ->useEverywhere() // novo método
```

No `register(Panel $panel)`:

```php
if ($this->useEverywhere) {
    app()->bind(\Filament\Forms\Components\TextInput::class, FluxInput::class);
    app()->bind(\Filament\Forms\Components\Textarea::class, FluxTextarea::class);
    app()->bind(\Filament\Forms\Components\Select::class, FluxSelect::class);
    app()->bind(\Filament\Forms\Components\Checkbox::class, FluxCheckbox::class);
    app()->bind(\Filament\Forms\Components\CheckboxList::class, FluxCheckboxGroup::class);
    app()->bind(\Filament\Forms\Components\Radio::class, FluxRadio::class);
    app()->bind(\Filament\Forms\Components\Toggle::class, FluxSwitch::class);
    app()->bind(\Filament\Forms\Components\OneTimeCodeInput::class, FluxOtpInput::class);
}
```

**Prós:**
- Zero edição em código do usuário.
- Cada Resource existente passa a renderizar com Flux.
- Reversível: remover `->useEverywhere()` volta ao Filament padrão.
- Segurança de tipo: subclasses Flux estendem Filament native, então `instanceof TextInput` continua verdadeiro.

**Contras:**
- Métodos próprios do Flux (`->fluxIcon()`, `->fluxClearable()`) ficam invisíveis para usuários que escrevem `TextInput::make()` — só aparecem via reflection / call. Não é problema funcional, só descoberta.
- PHPStan: `TextInput::make()` está tipado como `static` retornando `TextInput`. Métodos Flux ficam fora do contrato estático. Se o usuário quiser DSL Flux, ele importa `FluxInput::make()` diretamente.
- Pacotes Filament que estendem `TextInput` (ex.: outros plugins) também passam a ser swap-able **somente se** registrarem como `app(static::class)` — o que é o padrão Filament. Funciona em cascata.

### 3.B. Override de view paths

Em vez de trocar a classe, registrar um `View::addNamespace` ou prepend de
`view.paths` que contenha `text-input.blade.php` reescrito com `<flux:input>`.

**Prós:**
- Não toca em classes.
- Customizações do usuário em `make:filament-theme` continuam compondo.

**Contras:**
- Filament tem ~30 views de Form Field. Override total requer reescrever todas.
- Quebra quando Filament atualiza a view (precisa diff manual a cada minor).
- Usuário perde traits Flux (sem hook para `fluxIcon` etc.).

**Conclusão:** descartado. A binding swap (3.A) atinge o mesmo resultado com
~10 linhas de código e ganha a feature surface do Flux.

### 3.C. Híbrido: 3.A para Form Fields + Render Hooks

Mantém 3.A para Form Fields. Para outros componentes (que não auto-bind),
expor render hooks que substituem fragmentos específicos (ex.: notificações
do Filament por `<flux:toast>`). Isso é incremental e fica para releases
posteriores.

## 4. Plano de implementação

### Fase A — Toggle global no plugin (1 PR)

1. Adicionar `useEverywhere(bool $enabled = true): static` em `FilamentFluxPlugin`.
2. No `register(Panel $panel)`, quando ligado, registrar 8 bindings (lista
   acima).
3. Adicionar opção de granularidade: `useEverywhere(['inputs' => true,
   'textareas' => true, 'selects' => false, ...])` para o usuário ligar
   apenas o que quiser.
4. Pest: cobrir cada binding fazendo `livewire(TestForm::class)` com
   `TextInput::make(...)` e asserting o markup Flux.
5. README: documentar trade-offs (DSL, PHPStan).

### Fase B — Garantir paridade de comportamento (1 PR)

1. Auditar cada FluxXxx para reproduzir TODOS os hooks/observers que a
   classe Filament correspondente expõe (revealable, mask, datalist,
   afterStateUpdated, dehydrate, etc.).
2. Testes de paridade: rodar mesma `Resource` antes/depois do flag e comparar
   estado salvo no banco — deve ser idêntico.
3. Documentar features que não migram (ex.: `Select::searchable()` continua
   funcionando para fetch server-side, mas a UI vira `<select>` nativo, sem
   typeahead client-side).

### Fase C — Bindings condicionais por feature (futuro)

Permitir ao usuário cancelar swap por componente individual:

```php
FilamentFluxPlugin::make()
    ->useEverywhere()
    ->disableSwapFor([Select::class]); // mantém Select Filament onde for usado
```

Implementação: o método remove a binding antes de re-registrar, ou guarda lista
de excluídos consultada no boot.

### Fase D — Auto-swap para Pro (lançado em filament-flux-pro)

Quando `filament-flux-pro` instalado, plugin Pro estende `useEverywhere` para
incluir bindings de DatePicker/TimePicker/DateTimePicker/ColorPicker/RichEditor/
FileUpload/TagsInput → FluxXxx Pro. Mesmo padrão.

### Fase E — Auto-swap para Notifications/Toast/Modal (futuro)

Filament Notifications usa `<x-filament::notifications>` no body. Possível
publicar a view substituindo por `<flux:toast.group>` listening em eventos
`toast-show` (FluxToast já dispatches). Render hook + view override.

## 5. Riscos e mitigações

| Risco | Mitigação |
|---|---|
| Plugin de terceiros estende `TextInput` esperando view própria. | Subclasse continua resolvida via `app(static::class)` — não é alvo do binding. Apenas chamadas literais a `TextInput::make()` sofrem swap. |
| Usuário customizou view via `viewIdentifier` ou `view()` direto. | Filament ainda usa `static::$view` como default; `view()` explícito do usuário sempre vence. Auto-swap não interfere. |
| Atualizações futuras do Filament adicionam métodos que conflitam com nossos `flux*()`. | Nossos métodos têm prefixo `flux` → colisão improvável. Auditoria de release a cada upgrade Filament. |
| State casts (HasEnum, NumberStateCast) do Filament dependem de propriedades. | FluxInput herda integralmente — mesma lógica. |
| `Select::searchable()` perde busca client-side ao virar `<select>` nativo. | Documentar; oferecer opt-out granular. Resolve totalmente quando o Pro entrar com `<flux:select variant="combobox">`. |

## 6. Critérios de sucesso

- `useEverywhere()` em um Resource real (PostResource demo) renderiza todos
  os fields com Flux sem alterar uma linha do Resource.
- `composer test` continua verde com a flag ligada e desligada.
- PHPStan level 5 não regride.
- Bench: comparação de KB do bundle JS/CSS antes/depois — esperado redução
  no Pro ao trocar TipTap pelo `<flux:editor>`.

## 7. O que NÃO está no escopo

- Substituir Filament Tables UI inteira pelo `<flux:table>` (Pro). Filament
  Tables tem 30+ features (filtros, group headers, summaries, reorder). Auto-swap
  inviável.
- Substituir Filament Notifications pelo `<flux:toast>` antes de Fase E.
- Substituir o Builder ou Repeater. Sem equivalente Flux.

## 8. Navegação: Sidebar, Topbar e shell do painel

Esta camada é **estrutural**, não componente isolado. Container binding (3.A)
não atinge — o painel renderiza via Blade components nomeados
(`<x-filament-panels::sidebar>`, `<x-filament-panels::topbar>`,
`<x-filament-panels::layout.index>`, etc.) carregados pelo namespace
`filament-panels::` registrado pelo `Filament\PanelsServiceProvider`.

Mapeamento Flux → Filament:

| Filament (panel shell) | Flux primitive | Onde mora |
|---|---|---|
| `<x-filament-panels::sidebar>` (container) | `<flux:sidebar>` | `vendor/filament/filament/resources/views/components/sidebar/group.blade.php` + `item.blade.php` |
| navigation items dentro do sidebar | `<flux:navlist>` + `<flux:navlist.item>` | `vendor/filament/filament/resources/views/components/sidebar/item.blade.php` |
| sidebar group heading | `<flux:navlist.group>` | `vendor/filament/filament/resources/views/components/sidebar/group.blade.php` |
| `<x-filament-panels::topbar>` | `<flux:header>` + `<flux:navbar>` | `vendor/filament/filament/resources/views/components/topbar/item.blade.php` |
| topbar nav items | `<flux:navbar.item>` | idem |
| `<x-filament-panels::layout.base>` (HTML/body) | wrapper com `<flux:main>` | `vendor/filament/filament/resources/views/components/layout/base.blade.php` |
| `<x-filament-panels::layout.index>` (sidebar + main) | `<flux:sidebar>` + `<flux:main>` | `vendor/filament/filament/resources/views/components/layout/index.blade.php` |
| theme switcher | `<flux:dropdown>` com modos | `vendor/filament/filament/resources/views/components/theme-switcher.blade.php` |

### 8.1. Por que render hooks não bastam

Filament expõe ~40 render hooks (`PanelsRenderHook::SIDEBAR_*`,
`TOPBAR_*`, `PAGE_*`). Eles permitem **inserir** fragmentos antes/depois
de slots, mas:

- Não trocam o markup do item de navegação em si.
- Não substituem o container (sidebar/topbar) inteiro.
- Não removem a estrutura Filament-padrão; só sobrepõem.

Bom para: badges em items, banners no topo, footer custom.
Insuficiente para: trocar TODO o sidebar pelo `<flux:sidebar>`.

### 8.2. Estratégia: override de Blade components via prepend de view path

`PanelsServiceProvider` registra:

```php
Blade::componentNamespace('Filament\\Panels\\View\\Components', 'filament-panels');
```

Com isso, `<x-filament-panels::sidebar>` resolve em
`Filament\Panels\View\Components\Sidebar`, que por sua vez usa a view
`filament-panels::components.sidebar`.

Para substituir, fazemos no `register(Panel $panel)` do nosso plugin:

```php
View::prependLocation(__DIR__ . '/../resources/views/panels-overrides');
```

Estrutura paralela:

```
resources/views/panels-overrides/
└── vendor/
    └── filament-panels/
        └── components/
            ├── sidebar/
            │   ├── group.blade.php   ← flux:navlist.group
            │   └── item.blade.php    ← flux:navlist.item
            ├── topbar/
            │   └── item.blade.php    ← flux:navbar.item
            └── layout/
                ├── base.blade.php
                └── index.blade.php
```

Como o `prependLocation` adiciona o caminho **na frente** da resolução,
qualquer `view('filament-panels::components.sidebar.item')` cai na nossa
versão antes da do vendor.

### 8.3. Fases para Sidebar/Topbar

**Fase G1 — Navegação read-only (1 PR):**

1. Override de `sidebar/item.blade.php` e `sidebar/group.blade.php` para
   emitir `<flux:navlist.item>` / `<flux:navlist.group>` lendo o `$item`
   ou `$group` do Filament (mesmas variáveis Blade que o original).
2. Override de `topbar/item.blade.php` para `<flux:navbar.item>`.
3. Toggle no plugin: `useFluxNavigation(bool $enabled = true)`.
4. Testes Dusk para confirmar visual + cliques abrem links corretos.

**Fase G2 — Shell completo (1 PR):**

1. Override de `layout/index.blade.php` para envolver em
   `<flux:sidebar>` + `<flux:main>`.
2. Mover render hooks Filament para slots equivalentes do flux shell.
3. Adicionar opt-out granular: `useFluxNavigation(['sidebar' => true,
   'topbar' => false])`.

**Fase G3 — User menu, theme switcher, notifications panel:**

1. Substituir user menu pelo `<flux:dropdown>` com `<flux:profile>`.
2. Substituir theme-switcher pelo padrão flux `Flux.applyAppearance(...)`.
3. Notifications: render hook + `<flux:toast.group>`.

### 8.4. Riscos específicos da camada estrutural

| Risco | Mitigação |
|---|---|
| Filament v5.x mudar a assinatura/variáveis das views vendor entre minor releases. | Fixar a versão Filament suportada por release do plugin. CI matrix testa cada minor. Ao detectar diff, regenerar nossas overrides. |
| Plugins de terceiros (RefreshSidebar, Topbar customizado) também dependem das views originais. | Documentar incompatibilidade conhecida; dar opt-out por namespace (ex.: `useFluxNavigation()->except('sidebar')`). |
| Theme switcher do Filament dispara `theme-changed` que nosso bridge JS já intercepta. | Já resolvido em fase 1 (script bridge `theme-changed` ↔ `flux.appearance`). |
| Acessibilidade: `<flux:navlist>` tem ARIA próprio diferente do markup Filament. | Auditoria axe-core via Pest browser tests. |
| Custom resource icons/badges no sidebar dependem de slots Filament-específicos. | Reproduzir slots equivalentes nos overrides com mesmas variáveis (`$badge`, `$badgeColor`, `$icon`). |

### 8.5. Critério de sucesso para a navegação

- Painel demo (`PostResource`) com sidebar Flux nativo.
- Theme switcher e dark mode continuam funcionando.
- Render hooks Filament continuam disparando dentro dos slots novos
  (compatibilidade backward).
- Sem regressão em testes existentes.

## 9. Próximos passos sugeridos

1. Aprovar este plano.
2. Implementar Fase A (Form Fields auto-bind) em branch `feat/use-everywhere`.
3. Implementar Fase G1 (sidebar/topbar item overrides) em branch
   `feat/flux-navigation`.
4. Mesclar separadamente e taggear `1.1.0` (Form Fields) e `1.2.0`
   (navegação) — facilita rollback se algum minor do Filament quebrar
   uma das camadas.
5. Acompanhar issues por 1–2 semanas antes de avançar para Fases B/G2.
