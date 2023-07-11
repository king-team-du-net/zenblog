<?php

declare(strict_types=1);

namespace App\Twig\Components\Posts;

use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(name: 'post_count', template: 'components/posts/post_count.html.twig')]
final class PostCountComponent
{
    use DefaultActionTrait;

    public function __construct(
        private PostRepository $postRepository
    ) {
    }

    public function getPostCount(): int
    {
        return $this->postRepository->count([]);
    }
}
