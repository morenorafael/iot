<?php

namespace Morenorafael\Iot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Morenorafael\Iot\Home device(string $deviceId)
 * @method static \Morenorafael\Iot\Home\Socket socket(?string $deviceId = null)
 * @method static \Morenorafael\Iot\Home\Led led(?string $deviceId = null)
 */
class Home extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'morenorafael.home';
    }
}