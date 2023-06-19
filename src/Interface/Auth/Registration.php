<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\Mailer\MailSenderInterface;
use App\Interface\Mailer\Mail\RegistrationMail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class Registration implements RegistrationInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly MailSenderInterface $mailSender
    ) {
    }

    public function __invoke(User $user): void
    {
        /** @var string $plainPassword */
        $plainPassword = $user->getPlainPassword();

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
        $user->setRegistrationToken(Uuid::v4());

        $this->em->persist($user);
        $this->em->flush();

        $this->mailSender->with('user', $user)->send(RegistrationMail::class);
    }
}
