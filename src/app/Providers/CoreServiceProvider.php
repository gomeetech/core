<?php

namespace Gomee\Providers;

use Gomee\Commands\GomeeCommand;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadViewsFrom(__DIR__.'/../../resources/views', 'busibess');

        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            
            // $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

            // $this->publishes([
            //     __DIR__.'/../../database/migrations' => database_path('migrations'),
            // ], 'busibess-migrations');

            // $this->publishes([
            //     __DIR__.'/../../resources/views' => base_path('resources/views/vendor/business'),
            // ], 'busibess-views');
            

            $this->commands([
                GomeeCommand::class
            ]);
        }
    }

    
}
