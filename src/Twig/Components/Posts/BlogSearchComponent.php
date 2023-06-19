<?php

declare(strict_types=1);

namespace App\Twig\Components\Posts;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(name: 'blog_search', template: 'components/posts/blog_search.html.twig')]
final class BlogSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    /**
     * @return array<Post>
     */
    public function getPosts(): array
    {
        return $this->postRepository->findBySearchQuery($this->query);
    }
}
