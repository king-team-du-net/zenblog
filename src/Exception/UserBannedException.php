<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

final class UserBannedException extends CustomUserMessageAuthenticationException
{
    public function __construct(
        string $message = 'This account has been blocked',
        array $messageData = [],
        int $code = 0, \Throwable $previous = null
    ) {
        parent::__construct($message, $messageData, $code, $previous);
    }
}
