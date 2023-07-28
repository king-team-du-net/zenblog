<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Mailer;

use App\Interface\Mailer\Mail\MailInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MailSender implements MailSenderInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $options = [];

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $sender,
        private readonly ParameterBagInterface $params,
        private readonly ContainerInterface $container
    ) {
    }

    public function with(string $name, mixed $value): MailSenderInterface
    {
        if (isset($this->options[$name])) {
            throw new \InvalidArgumentException(sprintf('Option "%s" is already set.', $name)); // @codeCoverageIgnore
        }

        $this->options[$name] = $value;

        return $this;
    }

    public function send(string $mailClass): void
    {
        if (!$this->container->has($mailClass)) {
            throw new \InvalidArgumentException(sprintf('Mail "%s" must implement MailInterface.', $mailClass)); // @codeCoverageIgnore
        }

        /** @var MailInterface $mailInterface */
        $mailInterface = $this->container->get($mailClass);

        $resolver = new OptionsResolver();
        $mailInterface->configureOptions($resolver);
        $options = $resolver->resolve($this->options);

        $templatedEmail = (new TemplatedEmail())
            ->from(new Address(
                $this->sender,
                $this->params->get('website_name')
            ))
        ;

        $mailInterface->build($templatedEmail, $options);

        $this->mailer->send($templatedEmail);
    }
}
