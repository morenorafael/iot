<?php

namespace Morenorafael\Iot\Contracts;

interface Repository
{
    public function generalDevicesControl(string $deviceId): Commands;
}