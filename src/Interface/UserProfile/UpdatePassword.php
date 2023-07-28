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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UpdatePassword implements UpdatePasswordInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function __invoke(User $user): void
    {
        /** @var string $plainPassword */
        $plainPassword = $user->getPlainPassword();

        $user->setPassword($this->hasher->hashPassword($user, $plainPassword));

        $this->em->flush();
    }
}
