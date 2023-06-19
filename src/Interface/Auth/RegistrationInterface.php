<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use App\Entity\User;

interface RegistrationInterface
{
    public function __invoke(User $user): void;
}
