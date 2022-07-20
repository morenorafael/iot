<?php

namespace Morenorafael\Iot\Drivers\Tuya;

use Morenorafael\Iot\Contracts\Commands;
use Morenorafael\Iot\Contracts\Repository;

class Tuya extends Client implements Repository
{
    public function __construct(protected array $config)
    {
        $this->getClient($config);
    }

    public function generalDevicesControl(string $deviceId): Commands
    {
        return new GeneralDevicesControl($this, $deviceId);
    }

    public function generalDevicesManagement(string $deviceId): GeneralDevicesManagement
    {
        return new GeneralDevicesManagement($this, $deviceId);
    }
}