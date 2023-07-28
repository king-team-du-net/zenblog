<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Auth;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;

interface ResendVerifInterface
{
    public function __invoke(Uuid $token, User $user): void;
}
