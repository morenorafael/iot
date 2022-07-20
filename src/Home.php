<?php

namespace Morenorafael\Iot;

use Morenorafael\Iot\Contracts\Commands;

class Home
{
    protected Commands $control;

    public function __construct(protected \Morenorafael\Iot\Contracts\Repository $repository)
    {
    }

    public function device(string $deviceId): self
    {
        $this->control = $this->repository->generalDevicesControl($deviceId);

        return $this;
    }

    public function on(string $code = 'switch_1')
    {
        return $this->control->sendCommands([
            'code' => $code,
            'value' => true,
        ]);
    }

    public function off(string $code = 'switch_1', ?int $ttl = null)
    {
        if (!is_null($ttl)) {
            return $this->countdown($ttl);
        }

        return $this->control->sendCommands([
            'code' => $code,
            'value' => false,
        ]);
    }

    public function countdown(int $ttl, string $code = 'countdown_1')
    {
        return $this->control->sendCommands([
            'code' => $code,
            'value' => $ttl,
        ]);
    }
}