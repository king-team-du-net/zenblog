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

use App\Entity\ResetPasswordRequest;
use App\Interface\Mailer\Mail\ResetPasswordRequestMail;
use App\Interface\Mailer\MailSenderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

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
