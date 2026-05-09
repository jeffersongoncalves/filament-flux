# Fase 1 — Skeleton + Plugin + Asset Injection + Install Command

## Contexto

Pacote `jeffersongoncalves/filament-flux` integra componentes Flux UI (`livewire/flux`, free) ao Filament v5 como primitivas nativas (Form Fields, Infolist Entries, Table Columns, Actions). Esta é a Fase 1 de 5.

Objetivo desta fase: scaffold completo + plugin de painel + injeção de assets + comando `filament-flux:install` que prepara `theme.css` do painel.

NÃO escopo desta fase: Form Fields, Actions, Tables, Infolists (próximas fases).

## Stack

| Item | Versão |
|---|---|
| PHP | `^8.2` (alvo dev: 8.4) |
| Laravel | `^11.0 \|\| ^12.0 \|\| ^13.0` |
| Filament | `^5.0` |
| Livewire | `^4.0` |
| Tailwind CSS | `^4.0` |
| `livewire/flux` | `^2.14` |
| Spatie Package Tools | `^1.x` |
| Pest | `^3.x` |
| PHPStan/Larastan | level 5 |
| Pint | preset `laravel` |

## Tarefas

### 1. `composer.json`

- `name`: `jeffersongoncalves/filament-flux`
- `description`: "Filament v5 plugin exposing Livewire Flux UI components as native Form Fields, Table Columns, Infolist Entries and Actions."
- `type`: `library`
- `license`: `MIT`
- `keywords`: `["filament", "flux", "livewire", "tailwind", "laravel"]`
- `authors`: Jefferson Simao Goncalves
- `require`:
  - `php: ^8.2`
  - `filament/filament: ^5.0`
  - `livewire/flux: ^2.14`
  - `spatie/laravel-package-tools: ^1.16`
- `require-dev`:
  - `larastan/larastan`
  - `laravel/pint`
  - `orchestra/testbench: ^9.0 || ^10.0`
  - `pestphp/pest`
  - `pestphp/pest-plugin-laravel`
- `autoload.psr-4`: `Jeffersongoncalves\\FilamentFlux\\: src/`
- `autoload-dev.psr-4`: `Jeffersongoncalves\\FilamentFlux\\Tests\\: tests/`
- `extra.laravel.providers`: `["Jeffersongoncalves\\FilamentFlux\\FilamentFluxServiceProvider"]`
- `scripts`:
  - `analyse`: `vendor/bin/phpstan analyse`
  - `format`: `vendor/bin/pint`
  - `test`: `vendor/bin/pest`

### 2. `FilamentFluxServiceProvider`

`src/FilamentFluxServiceProvider.php`:

```php
namespace Jeffersongoncalves\FilamentFlux;

use Jeffersongoncalves\FilamentFlux\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentFluxServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-flux')
            ->hasConfigFile()
            ->hasViews('filament-flux')
            ->hasCommands([InstallCommand::class]);
    }
}
```

### 3. `FilamentFluxPlugin`

`src/FilamentFluxPlugin.php` implementa `Filament\Contracts\Plugin`:

- Fluent API:
  - `make(): static`
  - `getId(): string` → `'filament-flux'`
  - `register(Panel $panel): void` — registra render hooks
  - `boot(Panel $panel): void` — no-op por enquanto
  - `get(): static` — helper estático (`filament(static::getId())`)
- Métodos de configuração (todos retornam `static`):
  - `scopeClass(?string $class = 'filament-flux-scope'): static`
  - `injectAppearance(bool $inject = true): static`
  - `injectScripts(bool $inject = true): static`
  - `bridgeTheme(bool $bridge = true): static`
- Getters internos para uso pelos render hooks.

### 4. Render hooks

Em `register(Panel $panel)`:

- `PanelsRenderHook::HEAD_END` → render `filament-flux::render-hooks.flux-appearance` se `injectAppearance` ou `bridgeTheme`
- `PanelsRenderHook::BODY_END` → render `filament-flux::render-hooks.flux-scripts` se `injectScripts`
- `PanelsRenderHook::PAGE_START` → `<div class="{{ $scopeClass }}">` se `scopeClass !== null`
- `PanelsRenderHook::PAGE_END` → `</div>` (mesma condição)

Views:

- `resources/views/render-hooks/flux-appearance.blade.php`:
  ```blade
  @if($injectAppearance) @fluxAppearance @endif
  @if($bridgeTheme)
      <link rel="stylesheet" href="{{ asset('vendor/filament-flux/filament-flux.css') }}">
  @endif
  ```
- `resources/views/render-hooks/flux-scripts.blade.php`:
  ```blade
  @if($injectScripts) @fluxScripts @endif
  ```

Render hooks devem usar `fn (): string => view(...)->render()` com bind das flags.

### 5. `config/filament-flux.php`

```php
return [
    'scope_class' => 'filament-flux-scope',
    'inject_appearance' => true,
    'inject_scripts' => true,
    'bridge_theme' => true,
    'live_debounce' => 500,
];
```

### 6. CSS bridge

`resources/css/filament-flux.css`:

```css
.filament-flux-scope {
    --color-accent: var(--fi-color-primary-500, oklch(0.75 0.15 250));
    --color-accent-content: var(--fi-color-primary-50, white);
    --color-zinc-50: var(--fi-color-gray-50);
    --color-zinc-100: var(--fi-color-gray-100);
    --color-zinc-200: var(--fi-color-gray-200);
    --color-zinc-300: var(--fi-color-gray-300);
    --color-zinc-400: var(--fi-color-gray-400);
    --color-zinc-500: var(--fi-color-gray-500);
    --color-zinc-600: var(--fi-color-gray-600);
    --color-zinc-700: var(--fi-color-gray-700);
    --color-zinc-800: var(--fi-color-gray-800);
    --color-zinc-900: var(--fi-color-gray-900);
    --color-zinc-950: var(--fi-color-gray-950);
}
```

Publicável via `--tag=filament-flux-assets` em `public/vendor/filament-flux/`.

### 7. `InstallCommand`

`php artisan filament-flux:install --panel=admin`

Comportamento:

1. Resolve panel ID (default: `admin`).
2. Detecta `resources/css/filament/{panel}/theme.css`. Se não existir, instrui usuário a rodar `php artisan make:filament-theme {panel}` primeiro (NÃO criar automaticamente — o comando do Filament faz mais coisas).
3. Lê o arquivo. Insere idempotentemente as linhas:
   ```css
   @source '../../../../vendor/livewire/flux/dist';
   @source '../../../../vendor/jeffersongoncalves/filament-flux/resources/views';
   ```
   Logo após o `@import 'tailwindcss';` ou `@import` do tema Filament. Se já existirem (qualquer ordem), não duplica.
4. Publica config e CSS bridge:
   - `php artisan vendor:publish --tag=filament-flux-config`
   - `php artisan vendor:publish --tag=filament-flux-assets`
5. Imprime checklist final:
   ```
   ✓ theme.css atualizado
   ✓ config publicada
   ✓ assets publicados

   Próximos passos:
     1. Adicionar FilamentFluxPlugin::make() no panel provider
     2. Rodar npm run build (ou npm run dev)
     3. Limpar view cache: php artisan view:clear
   ```

Use `Symfony\Component\Console\Output\ConsoleOutput` ou `Laravel\Prompts` para output bonito. Idempotência via regex check antes de inserir.

### 8. `Support/ThemeFileEditor`

`src/Support/ThemeFileEditor.php` — classe utilitária extraída do `InstallCommand`:

- `public function addSourceLines(string $themePath, array $lines): bool` — retorna `true` se modificou, `false` se nada a fazer (já existem todas).
- Regex case-insensitive para detectar `@source\s+['"]{path}['"]`.

### 9. `Support/AssetInjector`

`src/Support/AssetInjector.php` — helper estático para os render hooks:

- `public static function appearance(FilamentFluxPlugin $plugin): string`
- `public static function scripts(FilamentFluxPlugin $plugin): string`
- `public static function scopeOpen(FilamentFluxPlugin $plugin): string`
- `public static function scopeClose(FilamentFluxPlugin $plugin): string`

Centraliza a lógica de "renderizar só se a flag estiver ativa".

### 10. Stub

`stubs/theme.css.stub`:

```css
@import 'tailwindcss';

@import '../../../../vendor/filament/filament/resources/css/theme.css';

@source '../../../../app/Filament';
@source '../../../../resources/views/filament';
@source '../../../../vendor/livewire/flux/dist';
@source '../../../../vendor/jeffersongoncalves/filament-flux/resources/views';

@import '../../../../vendor/livewire/flux/dist/flux.css';

@custom-variant dark (&:where(.dark, .dark *));

@theme {
    /* tema do painel — customize aqui */
}
```

### 11. Pest setup

- `tests/Pest.php` — `uses(TestCase::class)->in('Feature', 'Unit');`
- `tests/TestCase.php` — extends `Orchestra\Testbench\TestCase`, registra `FilamentFluxServiceProvider`, `FilamentServiceProvider`, `LivewireServiceProvider`, `FluxServiceProvider`.
- `tests/Feature/PluginRegistrationTest.php`:
  - Plugin registra-se em painel
  - Render hooks injetam markup esperado quando flags ON
  - Render hooks NÃO injetam quando flags OFF
- `tests/Feature/InstallCommandTest.php`:
  - Comando falha amigavelmente se theme.css não existe
  - Comando insere `@source` lines em theme.css existente
  - Idempotência: rodar 2× não duplica linhas
- `tests/Unit/ThemeFileEditorTest.php`:
  - Detecta linhas existentes (case-insensitive)
  - Insere após `@import 'tailwindcss';`
  - Preserva conteúdo existente

### 12. Smoke test manual

Documentar no `tests/MANUAL_SMOKE.md`:

1. Criar app Laravel limpo
2. `composer require jeffersongoncalves/filament-flux:dev-main`
3. `php artisan filament:install --panels`
4. `php artisan make:filament-theme admin`
5. `php artisan filament-flux:install --panel=admin`
6. Adicionar plugin no panel provider
7. `npm install && npm run build`
8. Abrir painel → verificar:
   - `<flux-appearance>` ou `@fluxAppearance` no `<head>`
   - `@fluxScripts` antes do `</body>`
   - Sem erros JS no console
   - Adicionar `<flux:button>Test</flux:button>` numa Custom Page e ver renderizar estilizado

## Critérios de aceite

- [ ] `composer install` limpo
- [ ] `vendor/bin/phpstan analyse` — level 5 limpo
- [ ] `vendor/bin/pint --test` — sem diffs
- [ ] `vendor/bin/pest` — todos verdes
- [ ] Plugin registra-se em painel sem erros
- [ ] Render hooks injetam markup correto (testado via `assertSeeHtml`)
- [ ] InstallCommand idempotente (rodar 2× não duplica)
- [ ] Smoke test manual passou

## Arquivos de saída

```
filament-flux/
├── composer.json
├── phpstan.neon
├── pint.json
├── README.md (skeleton)
├── LICENSE.md (MIT)
├── config/
│   └── filament-flux.php
├── resources/
│   ├── css/
│   │   └── filament-flux.css
│   └── views/
│       └── render-hooks/
│           ├── flux-appearance.blade.php
│           └── flux-scripts.blade.php
├── src/
│   ├── FilamentFluxServiceProvider.php
│   ├── FilamentFluxPlugin.php
│   ├── Commands/
│   │   └── InstallCommand.php
│   └── Support/
│       ├── AssetInjector.php
│       └── ThemeFileEditor.php
├── stubs/
│   └── theme.css.stub
└── tests/
    ├── Pest.php
    ├── TestCase.php
    ├── MANUAL_SMOKE.md
    ├── Feature/
    │   ├── PluginRegistrationTest.php
    │   └── InstallCommandTest.php
    └── Unit/
        └── ThemeFileEditorTest.php
```
