<?php

declare(strict_types=1);

namespace App\Interface\Post\Listing;

use App\Entity\Post;
use App\Entity\Comment;

interface ListCommentsInterface
{
    /**
     * @return array{
     *      page: int,
     *      pages: int,
     *      limit: int,
     *      total: int,
     *      count: int,
     *      _links: array{
     *          self: array{href: string},
     *          next?: array{href: string}
     *      },
     *      _embedded: array{posts: array<array-key, Comment>}
     * }
     */
    public function __invoke(Post $post, int $page): array;
}
