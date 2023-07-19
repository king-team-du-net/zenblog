<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class AdministratorVoter extends Voter
{
    public function __construct(private readonly string $appEnvironment)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        return !in_array($attribute, ['IS_IMPERSONATOR']);
    }

    /**
     * {@inheritdoc}
     */
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
