<?php

namespace CamboSoftware\CamboAdmin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\Router;

class CamboAdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/cambo-admin.php',
            'cambo-admin'
        );

        $this->app->singleton('cambo-admin', function ($app) {
            return new CamboAdmin($app['config']->get('cambo-admin'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerMiddleware();
        $this->registerViews();
        $this->registerMigrations();
    }

    /**
     * Register the package commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\InstallCommand::class,
                Console\Commands\CrudCommand::class,
                Console\Commands\PageCommand::class,
                Console\Commands\ComponentCommand::class,
                Console\Commands\AddModuleCommand::class,
            ]);
        }
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__ . '/../config/cambo-admin.php' => config_path('cambo-admin.php'),
            ], 'cambo-admin-config');

            // Migrations
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'cambo-admin-migrations');

            // Seeders
            $this->publishes([
                __DIR__ . '/../database/seeders/' => database_path('seeders'),
            ], 'cambo-admin-seeders');

            // Vue Components
            $this->publishes([
                __DIR__ . '/../resources/js/Components/' => resource_path('js/Components'),
            ], 'cambo-admin-components');

            // Vue Pages
            $this->publishes([
                __DIR__ . '/../resources/js/Pages/' => resource_path('js/Pages'),
            ], 'cambo-admin-pages');

            // Composables
            $this->publishes([
                __DIR__ . '/../resources/js/Composables/' => resource_path('js/Composables'),
            ], 'cambo-admin-composables');

            // Plugins
            $this->publishes([
                __DIR__ . '/../resources/js/Plugins/' => resource_path('js/Plugins'),
            ], 'cambo-admin-plugins');

            // Views (Blade)
            $this->publishes([
                __DIR__ . '/../resources/views/' => resource_path('views'),
            ], 'cambo-admin-views');

            // Stubs
            $this->publishes([
                __DIR__ . '/../stubs/' => base_path('stubs/cambo-admin'),
            ], 'cambo-admin-stubs');

            // Lang files
            $this->publishes([
                __DIR__ . '/../resources/lang/' => resource_path('lang'),
            ], 'cambo-admin-lang');

            // Controllers
            $this->publishes([
                __DIR__ . '/../app/Http/Controllers/' => app_path('Http/Controllers'),
            ], 'cambo-admin-controllers');

            // All assets
            $this->publishes([
                __DIR__ . '/../config/cambo-admin.php' => config_path('cambo-admin.php'),
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
                __DIR__ . '/../database/seeders/' => database_path('seeders'),
                __DIR__ . '/../resources/js/Components/' => resource_path('js/Components'),
                __DIR__ . '/../resources/js/Pages/' => resource_path('js/Pages'),
                __DIR__ . '/../resources/js/Composables/' => resource_path('js/Composables'),
                __DIR__ . '/../resources/js/Plugins/' => resource_path('js/Plugins'),
                __DIR__ . '/../resources/views/' => resource_path('views'),
                __DIR__ . '/../resources/lang/' => resource_path('lang'),
                __DIR__ . '/../app/Http/Controllers/' => app_path('Http/Controllers'),
            ], 'cambo-admin');
        }
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        if (!config('cambo-admin.routes.enabled', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Only load auth routes if controllers have been published
        if (config('cambo-admin.modules.auth', true) &&
            class_exists(\App\Http\Controllers\Auth\LoginController::class)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        }
    }

    /**
     * Register the package middleware.
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('role', Http\Middleware\CheckRole::class);
        $router->aliasMiddleware('permission', Http\Middleware\CheckPermission::class);
        $router->aliasMiddleware('cambo.locale', Http\Middleware\SetLocale::class);
    }

    /**
     * Register the package views.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cambo-admin');
    }

    /**
     * Register the package migrations.
     */
    protected function registerMigrations(): void
    {
        if (config('cambo-admin.migrations.enabled', true)) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['cambo-admin'];
    }
}
