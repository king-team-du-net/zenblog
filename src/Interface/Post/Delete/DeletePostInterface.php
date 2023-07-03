<?php

declare(strict_types=1);

namespace App\Interface\Post\Delete;

use App\Entity\Post;

interface DeletePostInterface
{
    public function __invoke(Post $post): void;
}
