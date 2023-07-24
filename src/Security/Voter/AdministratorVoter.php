<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AdministratorVoter extends Voter
{
    public function __construct(private readonly string $appEnvironment)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return !\in_array($attribute, ['IS_IMPERSONATOR'], true);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $administrator = $token->getUser();

        if (!$administrator instanceof User) {
            return false;
        }

        if ('prod' === $this->appEnvironment) {
            return 'Administrator' === $administrator->getNickname() && 1 === $administrator->getId();
        }

        return 'Administrator' === $administrator->getNickname();
    }
}
