<?php

declare(strict_types=1);

namespace App\Interface\Post\Comment;

use App\Entity\Comment;

interface CommentPostInterface
{
    public function __invoke(Comment $comment): void;
}
