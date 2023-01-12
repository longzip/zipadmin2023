<?php

namespace NoiThatZip\Warehouse;

use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'noithatzip');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'noithatzip');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/warehouse.php', 'warehouse');

        // Register the service the package provides.
        $this->app->singleton('warehouse', function ($app) {
            return new Warehouse;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['warehouse'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/create_warehouses_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_warehouses_table.php'),
        ], 'migrations');
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/warehouse.php' => config_path('warehouse.php'),
        ], 'warehouse.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/noithatzip'),
        ], 'warehouse.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/noithatzip'),
        ], 'warehouse.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/noithatzip'),
        ], 'warehouse.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
