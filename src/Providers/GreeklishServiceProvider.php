<?php

namespace Otodev\Providers;

use Otodev\Greeklish\Greeklish;
use Illuminate\Support\ServiceProvider;

class GreeklishServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Greeklish::class, function ($app) {
            return new Greeklish();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
