<?php

namespace Jeffersongoncalves\FilamentFlux\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Panel;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Flux\FluxServiceProvider;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Jeffersongoncalves\FilamentFlux\FilamentFluxServiceProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\DataStore;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->shareEmptyErrorBag();
        $this->registerTestPanel();
    }

    /**
     * Pre-populate the shared error bag so Livewire's SupportValidation hook
     * doesn't crash when running components outside the HTTP `web` middleware
     * stack (where ShareErrorsFromSession would normally do this).
     */
    protected function shareEmptyErrorBag(): void
    {
        $errors = tap(new ViewErrorBag)->put('default', new MessageBag);

        $this->app['session']->put('errors', $errors);
        $this->app['view']->share('errors', $errors);
    }

    protected function registerTestPanel(): void
    {
        Filament::registerPanel(
            Panel::make()
                ->id('test')
                ->path('test')
                ->default()
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FluxServiceProvider::class,
            FilamentFluxServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('view.paths', [
            __DIR__.'/views',
            __DIR__.'/../resources/views',
            resource_path('views'),
        ]);
    }

    protected function getEnvironmentSetUp($app)
    {
        // Livewire 4.3.0 + Testbench 10 bug: Livewire's Mechanism::register()
        // calls app()->instance() too early, and the binding is overwritten
        // before the singleton is needed. Pin DataStore as a singleton so the
        // WeakMap inside it persists across calls within a single test.
        $app->singleton(DataStore::class);
    }
}
