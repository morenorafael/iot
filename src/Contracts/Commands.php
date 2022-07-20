<?php

namespace Morenorafael\Iot\Contracts;

interface Commands
{
    public function sendCommands(array $commands);
}