<?php

declare(strict_types=1);

namespace App\Interface\Mailer\Mail;

use Symfony\Component\Mime\Address;
use App\Entity\ResetPasswordRequest;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ResetPasswordRequestMail implements MailInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function build(TemplatedEmail $email, array $options = []): void
    {
        /** @var ResetPasswordRequest $resetPasswordRequest */
        $resetPasswordRequest = $options['reset_password_request'];

        $email
            ->to(
                new Address(
                    $resetPasswordRequest->getUser()->getEmail(),
                    $resetPasswordRequest->getUser()->getNickname()
                )
            )
            ->subject($this->translator->trans('Welcome to the website of') . ' ' . $this->params->get('website_name'))
            ->htmlTemplate('emails/reset_password_request.html.twig')
            ->context(['reset_password_request' => $resetPasswordRequest])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['reset_password_request']);
        $resolver->setAllowedTypes('reset_password_request', ResetPasswordRequest::class);
    }
}
