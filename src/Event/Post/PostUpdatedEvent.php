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

use App\Entity\Content;
use App\Event\Content\ContentUpdatedEvent;

final class PostUpdatedEvent extends ContentUpdatedEvent
{
    public function __construct(Content $content, Content $previous)
    {
        parent::__construct($content, $previous);
    }
}
