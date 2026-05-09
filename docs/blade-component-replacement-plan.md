# Plano: substituir componentes Blade do Filament por equivalentes Flux

> Status: planejamento. Continuação do `auto-replacement-plan.md` (que cobriu
> Form Fields, navegação e theme switcher). Este documento mapeia os
> componentes Blade restantes — `<x-filament::*>`, `<x-filament-schemas::*>`,
> `<x-filament-actions::*>`, `<x-filament-widgets::*>`, `<x-filament-notifications::*>` —
> para Flux primitives e propõe rollout faseado.

## 1. Mecânica

Cada subpackage do Filament v5 registra seu próprio namespace Blade no
respectivo `PackageServiceProvider`:

| Namespace | Subpackage | Origem das views |
|---|---|---|
| `filament` | `filament/support` | `vendor/filament/support/resources/views` |
| `filament-actions` | `filament/actions` | `vendor/filament/actions/resources/views` |
| `filament-forms` | `filament/forms` | `vendor/filament/forms/resources/views` |
| `filament-infolists` | `filament/infolists` | `vendor/filament/infolists/resources/views` |
| `filament-notifications` | `filament/notifications` | `vendor/filament/notifications/resources/views` |
| `filament-panels` | `filament/filament` | `vendor/filament/filament/resources/views` |
| `filament-schemas` | `filament/schemas` | `vendor/filament/schemas/resources/views` |
| `filament-tables` | `filament/tables` | `vendor/filament/tables/resources/views` |
| `filament-widgets` | `filament/widgets` | `vendor/filament/widgets/resources/views` |

Mesma técnica de Fase G1/G2/G3: `View::prependNamespace($ns, $hintPath)`. As
views ausentes em `$hintPath` caem automaticamente para o vendor — nenhuma
rewrite total é exigida.

## 2. Inventário (snapshot Filament v5.6.x)

### 2.1. `filament` (support)

`<x-filament::*>` — 14 componentes raíz + subgrupos:

| Componente | Subitems | Mapeamento Flux |
|---|---|---|
| `actions` | — | `<flux:button.group>` quando inline; `<flux:dropdown>` quando agrupado |
| `avatar` | — | `<flux:avatar>` ✅ |
| `badge` | — | `<flux:badge>` ✅ |
| `breadcrumbs` | — | `<flux:breadcrumbs>` ✅ |
| `button` | `button.index`, `button.group` | já coberto via `FluxAction` (Fase 3) |
| `callout` | — | `<flux:callout>` ✅ |
| `card` | — | `<flux:card>` ✅ |
| `dropdown` | `dropdown.header`, `dropdown.list`, `dropdown.list.item`, `dropdown.index` | `<flux:dropdown>` + `<flux:menu>` ✅ |
| `empty-state` | — | sem direto — manter Filament |
| `fieldset` | — | `<flux:fieldset>` ✅ |
| `icon` | — | `<flux:icon>` ✅ |
| `icon-button` | — | `<flux:button square icon>` ✅ |
| `input` | `input.wrapper`, `input.affix-actions`, `input.affix-icon-color` | já coberto via class binding (FluxInput) |
| `link` | — | `<flux:link>` ✅ |
| `loading-indicator` | — | sem direto — manter Filament |
| `loading-section` | — | sem direto — manter Filament |
| `modal` | — | `<flux:modal>` ✅ |
| `pagination` | — | `<flux:pagination>` ✅ |
| `section` | — | `<flux:card>` (semântica equivalente) ✅ |
| `tabs` | `tabs.tab` | `<flux:tabs>` + `<flux:tab>` ⚠️ **Pro** |
| `toggle` | — | `<flux:switch>` ✅ |

### 2.2. `filament-actions`

| Componente | Mapeamento |
|---|---|
| `group` | `<flux:dropdown>` agrupando ações ✅ |
| `modals` | wrapper teleport — manter Filament (depende de Livewire state) |

### 2.3. `filament-forms` (Field views)

30 views de Field. **Já cobertas** via class binding em `useEverywhere()`
(Fases 1.1–1.4). Os arquivos legados continuam vindo do vendor para qualquer
field não substituído:

- `text-input`, `textarea`, `select`, `checkbox`, `checkbox-list`, `radio`,
  `toggle`, `one-time-code-input` → swapped via `FluxInput`/`FluxTextarea`/etc.
- `date-time-picker`, `color-picker`, `file-upload`, `rich-editor`,
  `markdown-editor`, `code-editor`, `slider`, `key-value`, `tags-input`,
  `repeater/*`, `builder/*`, `toggle-buttons/*`, `modal-table-select`,
  `table-select`, `field-wrapper`, `plain-field-wrapper`, `livewire-field` →
  manter Filament (sem equivalente direto OU é Pro).

### 2.4. `filament-schemas`

Componentes estruturais usados dentro de Form/Infolist Builders:

| Componente | Mapeamento |
|---|---|
| `actions` | `<flux:button.group>` para casos simples |
| `callout` | `<flux:callout>` ✅ |
| `empty-state` | sem direto |
| `fieldset` | `<flux:fieldset>` ✅ |
| `flex`, `grid` | manter Filament — utility CSS, não há "componente" Flux |
| `form` | manter Filament |
| `fused-group` | manter Filament — específico Filament |
| `image` | manter Filament |
| `livewire` | manter Filament |
| `section` | `<flux:card>` ✅ |
| `tabs` | `<flux:tabs>` ⚠️ Pro |
| `tabs.tab` | `<flux:tab>` ⚠️ Pro |
| `text` | `<flux:text>` ✅ |
| `unordered-list` | manter Filament |
| `wizard`, `wizard.step` | sem direto — manter Filament |

### 2.5. `filament-widgets`

| Componente | Mapeamento |
|---|---|
| `widget` | wrapper genérico — manter |
| `widgets` | grid wrapper — manter |
| `chart-widget` | `<flux:chart>` ⚠️ Pro |
| `stats-overview-widget` | layout com cards — `<flux:card>` ✅ |
| `stats-overview-widget.stat` | `<flux:stat>` ⚠️ Pro |
| `table-widget` | manter Filament |

### 2.6. `filament-notifications`

| Componente | Mapeamento |
|---|---|
| `notifications` (Livewire) | `<flux:toast.group>` envolvendo Notification objects | ⚠️ — Notification objects renderizam markup próprio; envolver não restilizar |
| `database-notifications` | manter Filament — DB notifications panel é altamente integrado |

## 3. Estratégia por slug

Adicionar mais slugs ao `useFluxNavigation()` ou criar nova API
`useFluxComponents()` para isolar — a segunda evita inflar o nome
"navigation". Decisão recomendada: **`useFluxComponents()`** novo método.

```php
FilamentFluxPlugin::make()
    ->useFluxComponents([
        'badge' => true,
        'avatar' => true,
        'callout' => true,
        'card' => true,        // x-filament::card e x-filament-schemas::section
        'breadcrumbs' => true,
        'fieldset' => true,
        'icon' => true,
        'iconButton' => true,
        'link' => true,
        'modal' => true,
        'pagination' => true,
        'dropdown' => true,
        'actionsGroup' => true,
        'schemaText' => true,
        'statsCard' => true,    // stats-overview-widget
    ]);
```

Defaults: todos `false`. Rollout incremental — usuário liga apenas o que
testou.

## 4. Mapa de paths (proposto)

```
resources/views/components-overrides/
├── filament/
│   ├── badge/                     ← prepend filament
│   ├── avatar/
│   ├── callout/
│   ├── card/
│   ├── breadcrumbs/
│   ├── fieldset/
│   ├── icon/
│   ├── iconButton/
│   ├── link/
│   ├── modal/
│   ├── pagination/
│   └── dropdown/
├── filament-actions/
│   └── actionsGroup/
├── filament-schemas/
│   ├── card/                       ← reutiliza override do `filament` mas em namespace diferente
│   ├── schemaText/
│   └── ...
└── filament-widgets/
    └── statsCard/
```

Cada slug tem seu próprio diretório por namespace. Plugin método novo:

```php
protected const COMPONENT_OVERRIDES = [
    'badge' => ['namespace' => 'filament', 'path' => 'filament/badge'],
    'avatar' => ['namespace' => 'filament', 'path' => 'filament/avatar'],
    // ...
    'actionsGroup' => ['namespace' => 'filament-actions', 'path' => 'filament-actions/actionsGroup'],
    'schemaText' => ['namespace' => 'filament-schemas', 'path' => 'filament-schemas/schemaText'],
];

protected function applyComponentOverrides(): void {
    foreach ($this->useFluxComponents as $slug => $on) {
        if (! $on) continue;
        $cfg = static::COMPONENT_OVERRIDES[$slug] ?? null;
        if (! $cfg) continue;
        View::prependNamespace($cfg['namespace'], "{$base}/{$cfg['path']}");
    }
}
```

## 5. Phasing sugerido

### Fase H1 — Componentes "atômicos" (1 PR)

Componentes 1:1 que não dependem de slots complexos:

- `badge`, `avatar`, `icon`, `iconButton`, `link`, `breadcrumbs`, `pagination`

Cada um: substituir o markup interno mantendo @props originais. Risco baixo.

### Fase H2 — Wrappers (`callout`, `card`, `fieldset`, `dropdown`)

Componentes que aceitam slot. Validar que slot inner renderiza corretamente
dentro de `<flux:callout>`/`<flux:card>`/etc.

### Fase H3 — `modal` (filament + actions + schemas)

Três pontos de uso:
1. `<x-filament::modal>` — usado por Action confirmation
2. `<x-filament-actions::modals>` — wrapper teleport para múltiplos modais
3. Modal-driven flows (FormAction, BulkAction)

Substituir `<x-filament::modal>` por `<flux:modal>` mantendo `wire:close` e
`x-on:cancel` events. `modals` (wrapper) fica como Filament. Tests E2E
obrigatórios via Pest browser ou Dusk.

### Fase H4 — Schema text (`<x-filament-schemas::text>`, `unordered-list`)

Fase rápida — text → `<flux:text>`. Lista mantém Filament por enquanto.

### Fase H5 — Stats overview widget

Substituir `stats-overview-widget` + `stat` por `<flux:card>` + `<flux:heading>`
+ `<flux:text>`. Layout grid permanece igual.

### Fase H6 — Notifications

Substituir o envelope de `<x-filament-notifications::notifications>` por
`<flux:toast.group>`. Notification objects continuam renderizando seu próprio
HTML (Filament-styled toast) DENTRO do toast.group flux. Documentar limitação.

### Fase H7 — Componentes Pro (deferido para `filament-flux-pro`)

- `tabs` → `<flux:tabs>`
- `chart-widget` → `<flux:chart>`
- `stats stat` → `<flux:stat>`
- `pagination` (richer) → `<flux:pagination>` Pro variant

## 6. Riscos

| Risco | Mitigação |
|---|---|
| Cada subpackage Filament evolui suas views entre minor releases. | Pinning explícito de Filament por release do plugin + CI matrix por minor + smoke tests por componente. |
| Tooltips, render hooks e Alpine state Filament-internos não traduzem para Flux. | Manter os attributes/x-data originais quando possível; documentar componentes que perderem hooks específicos. |
| Slot semantics divergem (named slots Filament vs slot único Flux). | Componentes com slots nomeados — `dropdown` (header+list+items) — exigem reestruturação cuidadosa, talvez não façam sentido swappar. |
| Pacotes terceiros estendem `<x-filament::section>` ou similares. | Override fica somente para uso direto do componente Filament; subclasses Blade do package terceiro continuam intactas pois não usam o namespace overridden. |
| Componentes "atômicos" (icon, badge) são chamados centenas de vezes por página. | Bench antes/depois para confirmar não há regressão de performance Blade. |

## 7. Critérios de sucesso

- Cada slug ligável independentemente; nenhum default ON sem confirmação.
- Painel demo (PostResource) renderiza com TODAS as flags H1–H6 ON sem
  regressão visual.
- `composer test` cobre por slug: assert prepend de namespace + render
  smoke test.
- PHPStan level 5 limpo.
- README com tabela atualizada de slugs e seus efeitos.

## 8. Out of scope (este plano)

- Substituir `repeater`, `builder`, `wizard`, `key-value`, `tags-input`,
  `slider`, `code-editor`, `rich-editor`, `markdown-editor`, `color-picker`,
  `date-time-picker`, `file-upload`. Todos têm Alpine state pesado e
  Livewire upload pipelines — replacement justificada apenas pelo Pro.
- Substituir `Table` UI inteira. Filament Table Builder tem feature surface
  vasta (filtros, group headers, summaries, reorder, bulk actions, etc.) e
  o `<flux:table>` Pro não cobre tudo.

## 9. Próximos passos

1. Aprovar este plano.
2. Implementar Fase H1 em branch `feat/flux-components-h1` e taggear `1.5.0`.
3. Cada Fase Hn em release minor própria (`1.6.0`, `1.7.0`, ...) para
   permitir rollback granular.
4. Quando `filament-flux-pro` estiver pronto, mover Fases H7 para lá.
