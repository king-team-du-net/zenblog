<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Mailer\Mail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface MailInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function build(TemplatedEmail $email, array $options = []): void;

    public function configureOptions(OptionsResolver $resolver): void;
}
