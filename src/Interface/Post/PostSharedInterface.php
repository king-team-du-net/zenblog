<?php

declare(strict_types=1);

namespace App\Interface\Post;

use App\Entity\Post;

interface PostSharedInterface
{
    public function __invoke(Post $post): void;
}
