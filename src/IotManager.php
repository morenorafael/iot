<?php

namespace Morenorafael\Iot;

use Illuminate\Foundation\Application;
use InvalidArgumentException;
use Morenorafael\Iot\Drivers\Tuya\Tuya;

class IotManager
{
    protected array $controllers = [];

    protected $customCreators = [];

    public function __construct(protected Application $app)
    {
    }

    public function controller($name = null): \Morenorafael\Iot\Contracts\Repository
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->controllers[$name] = $this->get($name);
    }

    public function driver($driver = null): \Morenorafael\Iot\Contracts\Repository
    {
        return $this->controller($driver);
    }

    protected function get($name): \Morenorafael\Iot\Contracts\Repository
    {
        return $this->controllers[$name] ?? $this->resolve($name);
    }

    protected function resolve($name): \Morenorafael\Iot\Contracts\Repository
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Git controller [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        } else {
            $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

            if (method_exists($this, $driverMethod)) {
                return $this->{$driverMethod}($config);
            } else {
                throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
            }
        }
    }

    public function getDefaultDriver(): string
    {
        return $this->app['config']['iot.default'];
    }

    public function setDefaultDriver(string $name): void
    {
        $this->app['config']['iot.default'] = $name;
    }

    protected function createTuyaDriver(array $config): Repository
    {
        return new Repository(new Tuya($config));
    }

    protected function callCustomCreator(array $config): mixed
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    protected function getConfig($name): ?array
    {
        if (! is_null($name) && $name !== 'null') {
            return $this->app['config']["iot.controllers.{$name}"];
        }

        return ['driver' => 'null'];
    }

    public function __call($method, $parameters)
    {
        return $this->controller()->$method(...$parameters);
    }
}