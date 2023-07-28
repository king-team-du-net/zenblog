<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\EventSubscriber\Revision;

use App\Entity\Revision\Revision;
use App\Event\Content\ContentUpdatedEvent;
use App\Event\Post\Revision\RevisionAcceptedEvent;
use App\Event\Post\Revision\RevisionRefusedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class RevisionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function onRevisionRefused(RevisionRefusedEvent $event): void
    {
        $event->getRevision()->setStatus(Revision::REJECTED);
        $event->getRevision()->setContent('');
        $this->em->flush();
    }

    public function onRevisionAccepted(RevisionAcceptedEvent $event): void
    {
        $content = $event->getRevision()->getTarget();
        $previous = clone $content;
        $content->setContent($event->getRevision()->getContent());

        $event->getRevision()->setStatus(Revision::ACCEPTED);
        $event->getRevision()->setContent('');

        $this->em->flush();
        $this->dispatcher->dispatch(new ContentUpdatedEvent($content, $previous));
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RevisionRefusedEvent::class => 'onRevisionRefused',
            RevisionAcceptedEvent::class => 'onRevisionAccepted',
        ];
    }
}
