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

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class PostVoter extends Voter
{
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @phpstan-param object $post
     */
    protected function supports(string $attribute, $post): bool
    {
        // this voter is only executed on Post objects and for three specific permissions
        return $post instanceof Post && \in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true);
    }

    /**
     * @param Post $post
     */
    protected function voteOnAttribute(string $attribute, $post, TokenInterface $token): bool
    {
        // We retrieve the user from the token
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // We check if the user is admin
        if ($this->security->isGranted(User::ADMIN)) {
            return true;
        }

        // We check the permissions
        switch ($attribute) {
            case self::EDIT:
                // We check if the user can edit
                return $this->canEdit();
                break;
            case self::DELETE:
                // We check if the user can delete
                return $this->canDelete();
                break;
        }

        return $user === $post->getAuthor();
    }

    private function canEdit(): bool
    {
        return $this->security->isGranted(User::EDITOR);
    }

    private function canDelete(): bool
    {
        return $this->security->isGranted(User::ADMIN);
    }
}
