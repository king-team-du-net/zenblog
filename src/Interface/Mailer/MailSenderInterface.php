<?php

declare(strict_types=1);

namespace App\Interface\Mailer;

use App\Interface\Mailer\Mail\MailInterface;

interface MailSenderInterface
{
    public function with(string $name, mixed $value): MailSenderInterface;

    /**
     * @param class-string<MailInterface> $mailClass
     */
    public function send(string $mailClass): void;
}
