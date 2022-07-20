<?php

namespace Morenorafael\Iot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Morenorafael\Iot\Contracts\Repository  controller(string|null $name = null)
 * @method static \Morenorafael\Iot\Contracts\Commands  generalDevicesControl(string $deviceId)
 */
class Iot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'morenorafael.iot';
    }
}