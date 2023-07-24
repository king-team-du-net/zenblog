<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Service;

use App\Entity\User;
use App\Event\User\UserDeletedRequestEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $user->setDeletedAt(new \DateTimeImmutable('+ '.(string) self::DAYS.' days'));
        $this->em->flush();
    }
}
