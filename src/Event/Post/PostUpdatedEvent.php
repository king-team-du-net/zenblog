<?php

declare(strict_types=1);

namespace App\Event\Post;

use App\Entity\Post;
use App\Event\Content\ContentUpdatedEvent;

final class PostUpdatedEvent extends ContentUpdatedEvent
{
    public function __construct(Post $content, Post $previous)
    {
        parent::__construct($content, $previous);
    }
}
