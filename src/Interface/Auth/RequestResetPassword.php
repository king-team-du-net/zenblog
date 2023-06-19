<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use Symfony\Component\Uid\Uuid;
use App\Entity\ResetPasswordRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\Mailer\MailSenderInterface;
use App\Interface\Mailer\Mail\ResetPasswordRequestMail;

final class RequestResetPassword implements RequestResetPasswordInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailSenderInterface $mailSender
    ) {
    }

    public function __invoke(ResetPasswordRequest $resetPasswordRequest): void
    {
        $resetPasswordRequest->setToken(Uuid::v4());

        $this->em->persist($resetPasswordRequest);
        $this->em->flush();

        $this->mailSender
            ->with('reset_password_request', $resetPasswordRequest)
            ->send(ResetPasswordRequestMail::class)
        ;
    }
}
