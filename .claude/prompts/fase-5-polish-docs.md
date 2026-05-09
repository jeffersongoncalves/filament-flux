# Fase 5 — Polimento, Documentação, Demo, CI

## Contexto

Fase final (5 de 5) do `jeffersongoncalves/filament-flux`. Pré-requisitos: Fases 1–4 entregues e verdes.

Objetivo: README completo, CHANGELOG, demo Resource, GitHub Actions, banner, FUNDING.yml, tag `1.0.0`.

## Tarefas

### 1. README.md

Seções obrigatórias:

1. **Banner** (gerado depois via skill `banner-generate`)
2. **Badges** — Packagist version, downloads, PHP, Laravel, Filament, license, CI status
3. **Aviso de versão** — destaque que pacote é Filament v5-only (NÃO portar para v3/v4)
4. **Compatibilidade** — tabela:
   | Filament | Laravel | PHP | Branch | Tag |
   |---|---|---|---|---|
   | 5.x | 11/12/13 | 8.2+ | `1.x` | `1.x.x` |
5. **Instalação**:
   ```bash
   composer require jeffersongoncalves/filament-flux
   php artisan filament-flux:install --panel=admin
   npm run build
   ```
6. **Configuração** — exemplo de plugin no panel provider, todos os métodos fluentes
7. **Uso — Form Fields** — tabela com cada componente + 1 exemplo
8. **Uso — Actions** — exemplos de FluxAction, FluxDropdown
9. **Uso — Table Columns** — exemplos
10. **Uso — Infolist Entries** — exemplos
11. **Theme bridge** — quando ligar/desligar, custom variables
12. **Troubleshooting**:
    - "Componentes Flux sem estilo" → conferir `@source` no theme.css
    - "Dupla scrollbar / overlay" → flag `injectScripts` ou modal Filament
    - "Dark mode não inverte" → conferir `class="dark"` no `<html>`
13. **Roadmap** — link para v1.1+ planejado
14. **Licença** — MIT
15. **Sponsorship** — link `.github/FUNDING.yml`

### 2. CHANGELOG.md

Padrão Keep a Changelog. Seed inicial:

```markdown
# Changelog

All notable changes to `filament-flux` will be documented in this file.

## [1.0.0] - YYYY-MM-DD

### Added
- Initial release supporting Filament v5
- Form Fields: FluxInput, FluxTextarea, FluxSelect, FluxCheckbox(Group), FluxRadio(Group), FluxSwitch
- Actions: FluxAction, FluxDropdown
- Modal helper for Custom Pages
- Tooltip trait
- Infolist Entries: FluxBadgeEntry, FluxAvatarEntry, FluxIconEntry, FluxTextEntry
- Table Columns: FluxBadgeColumn, FluxAvatarColumn, FluxIconColumn
- Theme bridge layer (opt-in)
- Render hook injection (@fluxAppearance / @fluxScripts)
- Install command with idempotent theme.css updates
```

Auto-update via workflow `update-changelog.yml` (já presente nos seus outros pacotes — copiar).

### 3. Demo Resource

`stubs/demo/PostResource.php` — Resource completa demonstrando TODOS os componentes:

- `FluxInput` (title, slug, email)
- `FluxTextarea` (excerpt)
- `FluxSelect` (category, status, tags)
- `FluxCheckboxGroup` + `FluxRadioGroup`
- `FluxSwitch` (published)
- `FluxAction` (publish, unpublish, archive)
- `FluxDropdown` agrupando 3 actions
- Tabela com `FluxBadgeColumn`, `FluxAvatarColumn`, `FluxIconColumn`
- Infolist com `FluxBadgeEntry`, `FluxTextEntry`

Publicável via `php artisan vendor:publish --tag=filament-flux-demo`. Documentar como rodar.

### 4. GitHub Actions

Copiar templates dos seus outros plugins, ajustando matriz:

`.github/workflows/tests.yml`:
```yaml
strategy:
  fail-fast: false
  matrix:
    php: ['8.2', '8.3', '8.4']
    laravel: ['11.*', '12.*', '13.*']
    livewire: ['^4.0']
    filament: ['^5.0']
    flux: ['^2.14']
    exclude:
      - { php: '8.2', laravel: '13.*' }
```

Outros workflows (idênticos aos seus outros plugins):

- `.github/workflows/phpstan.yml`
- `.github/workflows/fix-php-code-style-issues.yml`
- `.github/workflows/update-changelog.yml`
- `.github/workflows/dependabot-auto-merge.yml`
- `.github/dependabot.yml`

### 5. `.github/FUNDING.yml`

```yaml
github: [jeffersongoncalves]
patreon: jeffersongoncalves
custom: ['https://github.com/sponsors/jeffersongoncalves']
```

Usar template do skill `github-repo-setup`.

### 6. Banner

Gerar via skill `banner-generate` ou `portfolio-banner`. Captura metadata do `composer.json`.

### 7. Documentação MDX (opcional)

Pasta `docs/` com:
- `index.md` — landing
- `installation.md`
- `form-fields.md`
- `actions.md`
- `tables.md`
- `infolists.md`
- `theme-bridge.md`
- `troubleshooting.md`

Compatível com VitePress / Nextra (escolher um). Gerar `package.json` raiz se for VitePress.

### 8. Smoke test final completo

Documentar em `tests/MANUAL_SMOKE.md`:

1. App Laravel 13 limpo
2. `composer require jeffersongoncalves/filament-flux:^1.0`
3. `php artisan filament:install --panels`
4. `php artisan make:filament-theme admin`
5. `php artisan filament-flux:install --panel=admin`
6. Adicionar `FilamentFluxPlugin::make()` no panel provider
7. Publicar demo: `php artisan vendor:publish --tag=filament-flux-demo`
8. `npm install && npm run build`
9. `php artisan migrate`
10. Abrir `/admin/posts` — verificar:
    - Lista renderiza com badges/avatares/icons coloridos
    - Criar/editar form mostra todos os Flux fields funcionando
    - Validação aparece sem duplicação
    - Actions disparam corretamente
    - Dark mode inverte cores Flux + Filament coerentemente
    - Sem erros JS no console
    - Sem duplo Alpine no DOM

### 9. Pre-release checklist (do CLAUDE.md)

Antes do tag:

```bash
vendor/bin/pint
vendor/bin/phpstan analyse
vendor/bin/pest
```

Tudo verde → tag `1.0.0` (sem prefixo `v`, conforme convenção do monorepo).

### 10. Laravel Boost integration (opcional, alinhado com outros pacotes)

Adicionar `boost.json` com guidelines + skills do plugin. Ver skill `laravel-boost`.

## Critérios de aceite

- [ ] README completo com badges, instalação, exemplos de TODOS os componentes
- [ ] CHANGELOG `1.0.0` populado
- [ ] Demo Resource funcional via `vendor:publish --tag=filament-flux-demo`
- [ ] CI matriz verde em PHP 8.2/8.3/8.4 × Laravel 11/12/13
- [ ] Workflows `phpstan`, `tests`, `pint`, `update-changelog`, `dependabot` no lugar
- [ ] FUNDING.yml configurado
- [ ] Banner gerado e referenciado no README
- [ ] Smoke test manual completo passou em app Laravel 13 + Filament v5 zerado
- [ ] Tag `1.0.0` criada (sem prefixo `v`)
- [ ] Publicado no Packagist via webhook GitHub

## Arquivos de saída

```
README.md
CHANGELOG.md
LICENSE.md
.github/
├── workflows/
│   ├── tests.yml
│   ├── phpstan.yml
│   ├── fix-php-code-style-issues.yml
│   ├── update-changelog.yml
│   └── dependabot-auto-merge.yml
├── dependabot.yml
└── FUNDING.yml
art/
└── banner.png
stubs/
└── demo/
    ├── PostResource.php
    ├── PostResource/
    │   ├── Pages/
    │   │   ├── ListPosts.php
    │   │   ├── CreatePost.php
    │   │   ├── EditPost.php
    │   │   └── ViewPost.php
    │   └── Schemas/
    │       ├── PostForm.php
    │       └── PostInfolist.php
    └── migrations/
        └── create_posts_table.php
docs/ (opcional)
```
