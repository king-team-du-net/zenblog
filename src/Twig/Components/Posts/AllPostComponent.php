<?php

declare(strict_types=1);

namespace App\Twig\Components\Posts;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'all_post', template: 'components/posts/all_post.html.twig')]
final class AllPostComponent
{
    public function __construct(
        private PostRepository $postRepository
    ) {
    }

    /**
     * @return array<Post>
     */
    public function getAllPost(): array
    {
        return $this->postRepository->findAll();
    }
}
