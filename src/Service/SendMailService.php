<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class SendMailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ParameterBagInterface $params
    ) {
    }

    /** @throws TransportExceptionInterface */
    public function send(
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = (new TemplatedEmail())
            ->from(new Address(
                $this->params->get('website_no_reply_email'),
                $this->params->get('website_name'),
            ))
            ->to(new Address($to))
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context)
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $transport) {
            /* @var TransportExceptionInterface $transport */
            throw $transport;
        }
    }
}
