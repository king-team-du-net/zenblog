<?php

declare(strict_types=1);

namespace App\Interface\Post\Listing;

use App\Entity\Post;
use App\Repository\CommentRepository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function ceil;
use function count;

final class ListComments implements ListCommentsInterface
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function __invoke(Post $post, int $page): array
    {
        $total = $this->commentRepository->count(['post' => $post]);

        $comments = $this->commentRepository->getCommentsByPostAndPage($post, $page);

        $limit = 5;

        $pages = (int) ceil($total / $limit);

        $links = [
            'self' => [
                'href' => $this->urlGenerator->generate(
                    'api_blog_index_comments',
                    ['page' => $page, 'id' => $post->getId()]
                ),
            ],
        ];

        if ($page < $pages) {
            $links['next'] = [
                'href' => $this->urlGenerator->generate(
                    'api_blog_index_comments',
                    ['page' => $page + 1, 'id' => $post->getId()]
                ),
            ];
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'pages' => $pages,
            'total' => $total,
            'count' => count($comments),
            '_links' => $links,
            '_embedded' => ['comments' => $comments],
        ];
    }
}
