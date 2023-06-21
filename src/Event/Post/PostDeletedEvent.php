<?php

declare(strict_types=1);

namespace App\Event\Post;

use App\Entity\Post;
use App\Event\Content\ContentDeletedEvent;

final class PostDeletedEvent extends ContentDeletedEvent
{
    public function __construct(Post $content)
    {
        parent::__construct($content);
    }
}
