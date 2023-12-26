<?php

namespace SparkoutTech\Phonepe;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SparkoutTech\Phonepe\Phonepe;

class PhonepeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */

        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        Route::group([
            'namespace' => 'SparkoutTech\Phonepe\app\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web_v1.php');
        });

        if ($this->app->runningInConsole()) {
            //
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'phonepe');

        // Register the main class to use with the facade
        $this->app->singleton('phonepe', function () {
            return new Phonepe;
        });
    }
}
