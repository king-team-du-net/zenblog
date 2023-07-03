<?php

declare(strict_types=1);

namespace App\Interface\Post\Listing;

use App\Entity\Post;

interface ListPostsInterface
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
     *      _embedded: array{posts: array<array-key, Post>}
     * }
     */
    public function __invoke(int $page): array;
}
