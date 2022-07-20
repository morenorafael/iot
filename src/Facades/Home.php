<?php

namespace Morenorafael\Iot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Morenorafael\Iot\Home device(string $deviceId)
 */
class Home extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'morenorafael.home';
    }
}