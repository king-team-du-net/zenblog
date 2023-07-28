<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\UserProfile;

use App\Entity\User;

interface UpdateAvatarInterface
{
    public function __invoke(User $user): void;
}
