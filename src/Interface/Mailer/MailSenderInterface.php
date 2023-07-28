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

interface MailSenderInterface
{
    public function with(string $name, mixed $value): self;

    /**
     * @param class-string<MailInterface> $mailClass
     */
    public function send(string $mailClass): void;
}
