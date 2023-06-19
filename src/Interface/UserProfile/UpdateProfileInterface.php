<?php

declare(strict_types=1);

namespace App\Interface\UserProfile;

use App\Entity\User;

interface UpdateProfileInterface
{
    public function __invoke(User $user): void;
}
