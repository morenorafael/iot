<?php

namespace Morenorafael\Iot\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Morenorafael\Iot\IotManager;

class IotServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/iot.php', 'iot');

        $this->app->bind('morenorafael.iot', fn(Application $app) => new IotManager($app));

        $this->app->bind('morenorafael.iot.controller', fn (Application $app) => $app['morenorafael.iot']->driver());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/iot.php' => config_path('iot.php'),
        ], 'iot-config');
    }

    public function provides()
    {
        return [
            'morenorafael.iot', 'morenorafael.iot.controller',
        ];
    }
}