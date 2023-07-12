<?php

declare(strict_types=1);

namespace App\Interface\Post\Listing;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function ceil;
use function count;

final class ListPosts implements ListPostsInterface
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function __invoke(int $page): array
    {
        $total = $this->postRepository->count([]);

        $posts = $this->postRepository->getPostsByPage($page);

        $limit = 10;

        $pages = (int) ceil($total / $limit);

        $links = [
            'self' => [
                'href' => $this->urlGenerator->generate('api_blog_get_collection', ['page' => $page]),
            ],
        ];

        if ($page < $pages) {
            $links['next'] = [
                'href' => $this->urlGenerator->generate('api_blog_get_collection', ['page' => $page + 1]),
            ];
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'pages' => $pages,
            'total' => $total,
            'count' => count($posts),
            '_links' => $links,
            '_embedded' => ['posts' => $posts],
        ];
    }
}
