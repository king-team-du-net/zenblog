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

use App\Entity\Ad;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AdsVoter extends Voter
{
    public const DELETE = 'delete';
    public const EDIT = 'edit';

    public function __construct(
        private readonly bool $deleteAdByOwnerOnly,
        private readonly bool $updateAdByOwnerOnly
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::DELETE, self::EDIT], true) && $subject instanceof Ad;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Ad $ad */
        $ad = $subject;

        return match ($attribute) {
            self::DELETE => !$this->deleteAdByOwnerOnly || $ad->getAuthor() === $user,
            self::EDIT => !$this->updateAdByOwnerOnly || $ad->getAuthor() === $user,
            default => false,
        };
    }
}
