<?php

declare(strict_types=1);

namespace App\Interface\Post\Comment;

use App\Entity\Comment;

interface CommentEmInterface
{
    public function __invoke(Comment $comment): void;
}
