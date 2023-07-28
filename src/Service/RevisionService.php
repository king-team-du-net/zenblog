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

use App\Entity\Content;
use App\Entity\Revision\Revision;
use App\Entity\User;
use App\Event\Post\Revision\RevisionSubmittedEvent;
use App\Repository\Revision\RevisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class RevisionService
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly RevisionRepository $repository,
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * Suggest a change to the content.
     */
    public function submitRevision(Revision $revision): void
    {
        $revision->setCreatedAt(new \DateTime());

        $isNew = null === $revision->getId();
        if ($isNew) {
            $this->em->persist($revision);
        }

        $this->em->flush();

        if ($isNew) {
            $this->eventDispatcher->dispatch(new RevisionSubmittedEvent($revision));
        }
    }

    /**
     * Returns the current revision for the content/user or generates a new revision.
     */
    public function revisionFor(User $user, Content $content): Revision
    {
        $revision = $this->repository->findFor($user, $content);
        if (null !== $revision) {
            return $revision;
        }

        return (new Revision())
            ->setContent($content->getContent() ?: '')
            ->setTarget($content)
            ->setAuthor($user)
        ;
    }
}
