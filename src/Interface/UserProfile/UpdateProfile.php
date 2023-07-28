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
use Doctrine\ORM\EntityManagerInterface;

final class UpdateProfile implements UpdateProfileInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(User $user): void
    {
        $this->em->flush();
    }
}
