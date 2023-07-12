<?php

declare(strict_types=1);

namespace App\Interface\Messenger;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
interface CommandHandlerInterface /*extends MessageHandlerInterface*/
{
}
