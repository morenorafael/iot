<?php

namespace Morenorafael\Iot;

class Home
{
    protected string $deviceId;

    public function __construct(protected \Morenorafael\Iot\Contracts\Repository $repository)
    {
    }

    public function device(string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function on()
    {
        return $this->repository->generalDevicesControl($this->deviceId)->sendCommands([
            'code' => 'switch_1',
            'value' => true,
        ]);
    }

    public function off(?int $ttl = null)
    {
        if (!is_null($ttl)) {
            return $this->countdown($ttl);
        }

        return $this->repository->generalDevicesControl($this->deviceId)->sendCommands([
            'code' => 'switch_1',
            'value' => false,
        ]);
    }

    public function countdown(int $ttl)
    {
        return $this->repository->generalDevicesControl($this->deviceId)->sendCommands([
            'code' => 'countdown_1',
            'value' => $ttl,
        ]);
    }
}