<?php

declare(strict_types=1);

namespace App\Interface\Post\View;

use App\Entity\Post;

interface CreateViewInterface
{
    public function __invoke(Post $post): void;
}
