<?php

declare(strict_types=1);

namespace App\Interface\UserProfile;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use function sprintf;

final class UpdateProfile implements UpdateProfileInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(User $user): void
    {


        $this->em->flush();
    }
}
