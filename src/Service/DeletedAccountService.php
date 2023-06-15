<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Event\User\UserDeletedRequestEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class DeletedAccountService
{
    final public const DAYS = 5;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly AuthService $authService
    ) {
    }

    public function deleteUser(User $user, Request $request): void
    {
        $this->authService->logout($request);
        $this->dispatcher->dispatch(new UserDeletedRequestEvent($user));
        $user->setDeletedAt(new \DateTimeImmutable('+ ' . (string) self::DAYS . ' days'));
        $this->em->flush();
    }
}
