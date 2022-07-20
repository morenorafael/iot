<?php

namespace Morenorafael\Iot\Drivers\Tuya;

use Morenorafael\Iot\Contracts\Commands;

class GeneralDevicesControl implements Commands
{
    public function __construct(protected Tuya $client, protected string $deviceId)
    {
    }

    public function sendCommands(array $commands)
    {
        return $this->client->send('POST', "/v1.0/iot-03/devices/{$this->deviceId}/commands", [
            'body' => ['commands' => $commands],
        ]);
    }
}