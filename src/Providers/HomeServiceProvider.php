<?php

namespace Morenorafael\Iot\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Morenorafael\Iot\Home;

class HomeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('morenorafael.home', fn (Application $app) => new Home($app['morenorafael.iot']->driver()));
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