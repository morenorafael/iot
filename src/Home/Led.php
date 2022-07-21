<?php

namespace Morenorafael\Iot\Home;

use Morenorafael\Iot\Contracts\Commands;

class Led
{
    public function __construct(protected Commands $commands)
    {
    }

    public function on(string $code = 'switch_led')
    {
        return $this->commands->sendCommands([
            'code' => $code,
            'value' => true,
        ]);
    }

    public function off(string $code = 'switch_led', ?int $ttl = null)
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

    public function bright(float $percent, string $code = 'bright_value_v2')
    {
        $value = ($percent * 1000) / 100;

        return $this->commands->sendCommands([
            'code' => $code,
            'value' => $value,
        ]);
    }

    public function temperature(float $percent, string $code = 'temp_value_v2')
    {
        $value = ($percent * 1000) / 100;

        return $this->commands->sendCommands([
            'code' => $code,
            'value' => $value,
        ]);
    }

    public function colour(int $color, float $bright, float $contrast, string $code = 'colour_data_v2')
    {
        $h = $color;
        $s = ($contrast * 1000) / 100;
        $v = ($bright * 1000) / 100;

        return $this->commands->sendCommands([
            'code' => $code,
            'value' => compact('h', 's', 'v'),
        ]);
    }
}