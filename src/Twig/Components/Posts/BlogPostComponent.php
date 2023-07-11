<?php

declare(strict_types=1);

namespace App\Twig\Components\Posts;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'blog_post', template: 'components/posts/blog_post.html.twig')]
final class BlogPostComponent
{
    public int $id;

    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    /**
     * @return array<Post>
     */
    public function getPost(): Post
    {
        return $this->postRepository->find($this->id);
    }
}
