<?php

declare(strict_types=1);

namespace App\Interface\Mailer\Mail;

use App\Entity\User;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class RegistrationMail implements MailInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function build(TemplatedEmail $email, array $options = []): void
    {
        /** @var User $user */
        $user = $options['user'];

        $email
            ->to(new Address($user->getEmail(), $user->getNickname()))
            ->subject($this->translator->trans('Welcome to the website of') . ' ' . $this->params->get('website_name'))
            ->htmlTemplate('emails/registration.html.twig')
            ->context(['user' => $user])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['user']);
        $resolver->setAllowedTypes('user', User::class);
    }
}
