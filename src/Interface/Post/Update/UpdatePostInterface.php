<?php

declare(strict_types=1);

namespace App\Interface\Post\Update;

use App\Entity\Post;

interface UpdatePostInterface
{
    public function __invoke(Post $post): void;
}
