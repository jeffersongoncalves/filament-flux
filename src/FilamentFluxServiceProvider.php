<?php

namespace Jeffersongoncalves\FilamentFlux;

use Jeffersongoncalves\FilamentFlux\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentFluxServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-flux';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$name)
            ->hasTranslations()
            ->hasCommands([
                InstallCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__.'/../stubs/theme.css.stub' => base_path('stubs/filament-flux-theme.css.stub'),
        ], 'filament-flux-stubs');

        $this->publishes([
            __DIR__.'/../stubs/demo/Post.php.stub' => app_path('Models/Post.php'),
            __DIR__.'/../stubs/demo/PostResource.php.stub' => app_path('Filament/Resources/PostResource.php'),
            __DIR__.'/../stubs/demo/create_posts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_posts_table.php'),
        ], 'filament-flux-demo');
    }
}
