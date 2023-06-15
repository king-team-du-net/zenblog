<?php

declare(strict_types=1);

namespace App\Event\User;

use App\Entity\User;

final class UserCreatedEvent
{
    public function __construct(private readonly User $user, private readonly bool $usingOauth = false)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isUsingOauth(): bool
    {
        return $this->usingOauth;
    }
}
