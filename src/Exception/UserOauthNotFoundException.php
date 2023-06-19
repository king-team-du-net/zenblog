<?php

declare(strict_types=1);

namespace App\Exception;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Error returned when no user found matching OAUTH response.
 */
final class UserOauthNotFoundException extends AuthenticationException
{
    public function __construct(private readonly ResourceOwnerInterface $resourceOwner)
    {
    }

    public function getResourceOwner(): ResourceOwnerInterface
    {
        return $this->resourceOwner;
    }
}
