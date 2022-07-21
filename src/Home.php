<?php

namespace Morenorafael\Iot;

use Morenorafael\Iot\Contracts\Commands;
use Morenorafael\Iot\Home\Led;
use Morenorafael\Iot\Home\Socket;

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

    public function socket(?string $deviceId = null): Socket
    {
        if (!is_null($deviceId)) {
            $this->device($deviceId);
        }

        return new Socket($this->control);
    }

    public function led(?string $deviceId = null): Led
    {
        if (!is_null($deviceId)) {
            $this->device($deviceId);
        }

        return new Led($this->control);
    }
}