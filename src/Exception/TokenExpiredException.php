<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Exception thrown if a password resend request is made then
 * that a request is already in progress.
 */
final class TokenExpiredException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('', 0, null);
    }

    public function getMessageKey(): string
    {
        return 'Ongoing password reset.';
    }
}
