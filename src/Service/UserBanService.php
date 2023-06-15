<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Event\User\UserBannedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

final class UserBanService
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function ban(User $user): void
    {
        $user->setBannedAt(new \DateTime());
        $this->dispatcher->dispatch(new UserBannedEvent($user));
    }
}
