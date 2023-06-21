<?php

namespace App\Event\Post;

use App\Entity\Comment;
use Symfony\Contracts\EventDispatcher\Event;

final class CommentCreatedEvent extends Event
{
    public function __construct(
        protected readonly Comment $comment
    ) {
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }
}
