<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Security;

use App\Entity\User;
use App\Exception\AccountSuspendedException;
use App\Exception\TooManyInvalidCredentialsException;
use App\Exception\UserBannedException;
use App\Service\LoginAttemptService;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Blocks user authentication.
 */
final class UserChecker implements UserCheckerInterface
{
    public function __construct(
        private readonly LoginAttemptService $login,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * Check that the user has the right to connect.
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof User && $this->login->limitReachedFor($user)) {
            throw new TooManyInvalidCredentialsException();
        }

        if ($user instanceof User && $user->isSuspended()) {
            throw new AccountSuspendedException($user, $this->translator->trans('Your account has been suspended.'));
        }
    }

    /**
     * Verify that the logged in user has permission to continue.
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if ($user instanceof User && $user->isBanned()) {
            throw new UserBannedException();
        }

        /*if ($user instanceof User && null !== $user->getRegistrationToken()) {
            throw new UserNotFoundException();
        }*/

        /** @var User $user */
        if (!$user->isIsVerified()) {
            throw new CustomUserMessageAccountStatusException($this->translator->trans("You must validate your registration before {$user->getRegistrationTokenLifeTime()->format('d-m-Y-H-i-s')}"));
        }
    }
}
