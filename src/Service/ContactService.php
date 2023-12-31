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

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ContactService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $stack
    ) {
    }

    public function persistContact(Contact $contact): void
    {
        $contact->setIp($this->stack->getMainRequest()?->getClientIp());
        $contact->setIsSend(false);
        $contact->setCreatedAt(new \DateTime('now'));

        $this->em->persist($contact);
        $this->em->flush();
    }

    public function isSend(Contact $contact): void
    {
        $contact->setIsSend(true);

        $this->em->persist($contact);
        $this->em->flush();
    }
}
