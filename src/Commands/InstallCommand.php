<?php

namespace Jeffersongoncalves\FilamentFlux\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Jeffersongoncalves\FilamentFlux\Support\ThemeFileEditor;

class InstallCommand extends Command
{
    protected $signature = 'filament-flux:install
        {--panel=admin : Panel ID whose theme.css should be patched}
        {--no-publish : Skip vendor:publish steps}';

    protected $description = 'Patch the Filament panel theme.css with Flux @source paths and publish package assets';

    public function handle(ThemeFileEditor $editor): int
    {
        $panel = (string) $this->option('panel');
        $themePath = base_path("resources/css/filament/{$panel}/theme.css");

        $this->components->info("Installing filament-flux into panel [{$panel}]");

        if (! File::exists($themePath)) {
            $this->components->error("Theme file not found: {$themePath}");
            $this->components->warn(
                "Run `php artisan make:filament-theme {$panel}` first, then re-run this command."
            );

            return self::FAILURE;
        }

        $modified = $editor->addImportLines($themePath, [
            '../../../../vendor/livewire/flux/dist/flux.css',
            '../../../../vendor/jeffersongoncalves/filament-flux/dist/filament-flux.css',
        ]);

        if ($modified) {
            $this->components->info("Patched {$themePath}");
        } else {
            $this->components->info('theme.css already contains @source paths — nothing to do.');
        }

        if (! $this->option('no-publish')) {
            $this->callSilent('vendor:publish', [
                '--tag' => 'filament-flux-config',
                '--force' => false,
            ]);
            $this->components->info('Published config (skipped if already present).');
        }

        $this->newLine();
        $this->components->info('Next steps:');
        $this->components->bulletList([
            'Add FilamentFluxPlugin::make() to your panel provider',
            'Run `npm run build` (or `npm run dev`)',
            'Run `php artisan view:clear`',
        ]);

        return self::SUCCESS;
    }
}
