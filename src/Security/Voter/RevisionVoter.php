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

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class RevisionVoter extends Voter
{
    final public const ADD_REVISION = 'add_revision';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, [
            self::ADD_REVISION,
        ], true) && null === $subject;
    }

    /**
     * @param string  $attribute
     * @param Comment $subject
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return true;
    }
}
