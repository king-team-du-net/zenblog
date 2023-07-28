<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Event\Post\Revision;

use App\Entity\Revision\Revision;

class RevisionAcceptedEvent
{
    public function __construct(private readonly Revision $revision)
    {
    }

    public function getRevision(): Revision
    {
        return $this->revision;
    }
}
