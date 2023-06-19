<?php

declare(strict_types=1);

namespace App\Interface\Messenger;

interface CommandBusInterface
{
    public function dispatch(mixed $command): mixed;
}
