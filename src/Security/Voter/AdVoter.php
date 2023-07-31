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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AdVoter extends Voter
{
    final public const AD_CREATE = 'AD_CREATE';
    final public const AD_EDIT = 'AD_EDIT';
    final public const AD_DELETE = 'AD_DELETE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::AD_CREATE, self::AD_EDIT, self::AD_DELETE], true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::AD_EDIT, self::AD_DELETE => $this->canUpdateAd($user, $subject),
            self::AD_CREATE => true,
            default => false,
        };
    }

    private function canUpdateAd(User $user, Ad $ad): bool
    {
        return $ad->getAuthor()->getId() === $user->getId();
    }
}
