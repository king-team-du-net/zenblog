<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

final class TooManyInvalidCredentialsException extends CustomUserMessageAuthenticationException
{
    public function __construct(
        string $message = 'The account has been locked due to too many login attempts',
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $messageData, $code, $previous);
    }
}
