<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Messenger;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
interface QueryHandlerInterface /* extends MessageHandlerInterface */
{
}
