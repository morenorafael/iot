<?php

namespace Morenorafael\Iot\Drivers\Tuya;

class GeneralDevicesManagement
{
    public function __construct(protected Tuya $client, protected string $deviceId)
    {
    }

    public function getDeviceInformation()
    {
        return $this->client->send('GET', "/v1.1/iot-03/devices/{$this->deviceId}");
    }
}