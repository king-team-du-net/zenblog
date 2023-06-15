<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\LoginAttempt;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LoginAttemptRepository;

final class LoginAttemptService
{
    final public const ATTEMPTS = 3;

    public function __construct(
        private readonly LoginAttemptRepository $repository,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function addAttempt(User $user): void
    {
        $attempt = (new LoginAttempt())->setUser($user);
        $this->em->persist($attempt);
        $this->em->flush();
    }

    public function limitReachedFor(User $user): bool
    {
        return $this->repository->countRecentFor($user, 30) >= self::ATTEMPTS;
    }
}
