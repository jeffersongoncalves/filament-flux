<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->panel = 'admin';
    $this->themePath = base_path("resources/css/filament/{$this->panel}/theme.css");
    File::ensureDirectoryExists(dirname($this->themePath));
});

afterEach(function () {
    if (File::exists($this->themePath)) {
        File::delete($this->themePath);
    }
});

it('fails when theme.css is missing', function () {
    $exit = Artisan::call('filament-flux:install', [
        '--panel' => $this->panel,
        '--no-publish' => true,
    ]);

    expect($exit)->toBe(1);
    expect(Artisan::output())->toContain('Theme file not found');
});

it('patches theme.css with both @import and @source lines', function () {
    File::put($this->themePath, "@import \"tailwindcss\";\n\n@theme {\n}\n");

    $exit = Artisan::call('filament-flux:install', [
        '--panel' => $this->panel,
        '--no-publish' => true,
    ]);

    expect($exit)->toBe(0);

    $contents = File::get($this->themePath);
    expect($contents)
        ->toContain('@import "../../../../vendor/livewire/flux/dist/flux.css";')
        ->toContain('@import "../../../../vendor/jeffersongoncalves/filament-flux/dist/filament-flux.css";')
        ->toContain('@source "../../../../vendor/livewire/flux/stubs/resources/views/flux/**/*";')
        ->toContain('@source "../../../../vendor/jeffersongoncalves/filament-flux/resources/views/components/**/*";');
});

it('is idempotent — running twice does not duplicate', function () {
    File::put($this->themePath, "@import \"tailwindcss\";\n");

    Artisan::call('filament-flux:install', [
        '--panel' => $this->panel,
        '--no-publish' => true,
    ]);

    Artisan::call('filament-flux:install', [
        '--panel' => $this->panel,
        '--no-publish' => true,
    ]);

    $contents = File::get($this->themePath);
    expect(substr_count($contents, '@import "../../../../vendor/livewire/flux/dist/flux.css";'))->toBe(1);
    expect(substr_count($contents, '@import "../../../../vendor/jeffersongoncalves/filament-flux/dist/filament-flux.css";'))->toBe(1);
    expect(substr_count($contents, '@source "../../../../vendor/livewire/flux/stubs/resources/views/flux/**/*";'))->toBe(1);
    expect(substr_count($contents, '@source "../../../../vendor/jeffersongoncalves/filament-flux/resources/views/components/**/*";'))->toBe(1);
});
