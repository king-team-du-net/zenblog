<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Event\Post;

use App\Entity\Post;
use App\Event\Content\ContentCreatedEvent;

final class PostCreatedEvent extends ContentCreatedEvent
{
    public function __construct(Post $content)
    {
        parent::__construct($content);
    }
}
