<?php

namespace Morenorafael\Iot\Home;

use Morenorafael\Iot\Contracts\Commands;

class Socket
{
    public function __construct(protected Commands $commands)
    {
    }

    public function on(string $code = 'switch_1')
    {
        return $this->commands->sendCommands([
            'code' => $code,
            'value' => true,
        ]);
    }

    public function off(string $code = 'switch_1', ?int $ttl = null)
    {
        if (!is_null($ttl)) {
            return $this->countdown($ttl);
        }

        return $this->commands->sendCommands([
            'code' => $code,
            'value' => false,
        ]);
    }

    public function countdown(int $ttl, string $code = 'countdown_1')
    {
        return $this->commands->sendCommands([
            'code' => $code,
            'value' => $ttl,
        ]);
    }
}