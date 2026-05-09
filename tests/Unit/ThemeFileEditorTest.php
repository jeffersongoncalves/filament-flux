<?php

use Illuminate\Support\Facades\File;
use Jeffersongoncalves\FilamentFlux\Support\ThemeFileEditor;

beforeEach(function () {
    $this->themePath = sys_get_temp_dir().'/filament-flux-test-theme-'.uniqid().'.css';
    $this->editor = new ThemeFileEditor;
});

afterEach(function () {
    if (File::exists($this->themePath)) {
        File::delete($this->themePath);
    }
});

it('throws when theme file does not exist', function () {
    $this->editor->addImportLines('/nonexistent/path/theme.css', ['../foo']);
})->throws(RuntimeException::class);

it('inserts @import lines after @import tailwindcss', function () {
    File::put($this->themePath, "@import \"tailwindcss\";\n\n@theme {\n}\n");

    $modified = $this->editor->addImportLines($this->themePath, [
        '../../../../vendor/livewire/flux/dist/flux.css',
        '../../../../vendor/jeffersongoncalves/filament-flux/dist/filament-flux.css',
    ]);

    expect($modified)->toBeTrue();

    $contents = File::get($this->themePath);
    expect($contents)
        ->toContain('@import "../../../../vendor/livewire/flux/dist/flux.css";')
        ->toContain('@import "../../../../vendor/jeffersongoncalves/filament-flux/dist/filament-flux.css";');
});

it('is idempotent — running twice does not duplicate lines', function () {
    File::put($this->themePath, "@import \"tailwindcss\";\n");

    $first = $this->editor->addImportLines($this->themePath, ['../../../../vendor/livewire/flux/dist/flux.css']);
    $second = $this->editor->addImportLines($this->themePath, ['../../../../vendor/livewire/flux/dist/flux.css']);

    expect($first)->toBeTrue();
    expect($second)->toBeFalse();

    $occurrences = substr_count(File::get($this->themePath), '@import "../../../../vendor/livewire/flux/dist/flux.css";');
    expect($occurrences)->toBe(1);
});

it('detects existing @import lines regardless of quote style', function () {
    File::put(
        $this->themePath,
        "@import 'tailwindcss';\n@import '../../../../vendor/livewire/flux/dist/flux.css';\n"
    );

    $hasIt = $this->editor->hasImport(
        File::get($this->themePath),
        '../../../../vendor/livewire/flux/dist/flux.css'
    );

    expect($hasIt)->toBeTrue();
});

it('appends to end when no @import anchor exists', function () {
    File::put($this->themePath, "/* custom theme */\n");

    $modified = $this->editor->addImportLines($this->themePath, ['../foo.css']);

    expect($modified)->toBeTrue();
    expect(File::get($this->themePath))->toContain('@import "../foo.css";');
});

it('only adds missing entries when some already exist', function () {
    File::put(
        $this->themePath,
        "@import \"tailwindcss\";\n@import \"../existing.css\";\n"
    );

    $modified = $this->editor->addImportLines($this->themePath, [
        '../existing.css',
        '../new-one.css',
    ]);

    expect($modified)->toBeTrue();

    $contents = File::get($this->themePath);
    expect(substr_count($contents, '@import "../existing.css";'))->toBe(1);
    expect($contents)->toContain('@import "../new-one.css";');
});

it('inserts @source directives idempotently', function () {
    File::put($this->themePath, "@import \"tailwindcss\";\n");

    $first = $this->editor->addSourceLines($this->themePath, [
        '../../../../vendor/livewire/flux/stubs/resources/views/flux/**/*',
    ]);

    $second = $this->editor->addSourceLines($this->themePath, [
        '../../../../vendor/livewire/flux/stubs/resources/views/flux/**/*',
    ]);

    expect($first)->toBeTrue();
    expect($second)->toBeFalse();

    $contents = File::get($this->themePath);
    expect(substr_count($contents, '@source "../../../../vendor/livewire/flux/stubs/resources/views/flux/**/*";'))->toBe(1);
});

it('hasSource detects an existing @source line', function () {
    $contents = "@import \"tailwindcss\";\n@source '../views/flux/**/*';\n";

    expect($this->editor->hasSource($contents, '../views/flux/**/*'))->toBeTrue();
    expect($this->editor->hasSource($contents, '../missing'))->toBeFalse();
});
