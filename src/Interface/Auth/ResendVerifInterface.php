<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;

interface ResendVerifInterface
{
    public function __invoke(Uuid $token, User $user): void;
}
