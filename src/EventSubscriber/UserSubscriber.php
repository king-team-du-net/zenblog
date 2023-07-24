<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    /*
    public function beforeUserUpdated(BeforeEntityUpdatedEvent $event): void
    {
        $user = $event->getEntityInstance();

        if ($user instanceof User) {
            $this->hashPasswordUser($user);
        }
    }

    public function beforeUserPersisted(BeforeEntityPersistedEvent $event): void
    {
        $user = $event->getEntityInstance();

        if ($user instanceof User) {
            $this->hashPasswordUser($user);
        }
    }

    private function hashPasswordUser(User $user): void
    {
        if (null !== $user->getPlainPassword()) {
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
        }
    }
    */

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // BeforeEntityPersistedEvent::class => ['beforeUserPersisted'],
            // BeforeEntityUpdatedEvent::class => ['beforeUserUpdated'],
        ];
    }
}
