<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use App\Entity\ResetPasswordRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetPassword implements ResetPasswordInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(ResetPasswordRequest $resetPasswordRequest): void
    {
        $user = $resetPasswordRequest->getUser();

        /** @var string $plainPassword */
        $plainPassword = $user->getPlainPassword();

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));

        $this->em->remove($resetPasswordRequest);
        $this->em->flush();
    }
}
