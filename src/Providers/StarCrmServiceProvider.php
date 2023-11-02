<?php

namespace StarInsure\Crm\Providers;

use Illuminate\Support\ServiceProvider;

class StarCrmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                // Publish config
                __DIR__.'/../../config/crm.php' => config_path('crm.php'),
            ], 'starinsure-crm');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/crm.php', 'crm');

        // Register the main class to use with the facade
        $this->app->bind('starcrm', function () {
            return new \StarInsure\Crm\StarCrm(
                config('crm.auth_strategy'),
                config('crm.version')
            );
        });
    }
}
