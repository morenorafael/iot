<?php

namespace Morenorafael\Iot;

use Morenorafael\Iot\Contracts\Commands;

class Repository implements Contracts\Repository
{
    public function __construct(protected Contracts\Repository $controller)
    {
    }

    public function generalDevicesControl(string $deviceId): Commands
    {
        return $this->controller->generalDevicesControl($deviceId);
    }
}