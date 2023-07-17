<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class ResendVerif implements ResendVerifInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(Uuid $token, User $user): void
    {
        if ($user->getRegistrationToken() !== $token) {
            throw new AccessDeniedException();
        }

        if (null === $user->getRegistrationToken()) {
            throw new AccessDeniedException();
        }

        if (new \DateTime('now') > $user->getRegistrationTokenLifeTime()) {
            throw new AccessDeniedException();
        }

        $user->setIsVerified(true);
        $user->setRegistrationToken(null);
        $this->em->flush();
    }
}