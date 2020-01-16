<?php

namespace Lifeonscreen\Google2fa;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Lifeonscreen\Google2fa\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'google2fa');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__ . '/../config/nova-google2fa.php' => config_path('nova-google2fa.php'),
            ], 'lifeonscreen2fa.config');

            // Publishing the migrations.
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../resources/js/nova-google2fa.js' => public_path('vendor/nova-google2fa/nova-google2fa.js')
            ],  ['public', 'nova-google2fa']);

            $this->publishes([
                __DIR__ . '/../resources/css/tool.css' => public_path('vendor/nova-google2fa/nova-google2fa.css')
            ],  ['public', 'nova-google2fa']);
        }

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova/los/2fa')
            ->name('los.2fa.')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nova-google2fa.php', 'lifeonscreen2fa');
    }
}
