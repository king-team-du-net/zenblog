<?php

declare(strict_types=1);

namespace App\Interface\Post\Create;

use App\Entity\Post;

interface CreatePostInterface
{
    public function __invoke(Post $post): void;
}
