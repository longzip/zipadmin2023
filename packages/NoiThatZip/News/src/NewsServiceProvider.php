<?php

namespace NoiThatZip\News;

use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/../config/new.php', 'new');

        $this->app->singleton('new', function ($app) {
            return new News;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function provides()
    {
        return ['new'];
    }

     protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/new.php' => config_path('new.php'),
        ], 'new.config');

    }
}
