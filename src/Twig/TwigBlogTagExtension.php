<?php

namespace App\Twig;

use App\Entity\Post;
use Twig\TwigFunction;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use Twig\Extension\AbstractExtension;

final class TwigBlogTagExtension extends AbstractExtension
{
    public function __construct(
        private readonly TagRepository $repository,
        private readonly PostRepository $postRepository
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('blogTag', [$this, 'blogTag']),
            new TwigFunction('blogTagEmpty', [$this, 'blogTagEmpty']),
        ];
    }

    public function blogTag(): array
    {
        return $this->repository->findAll();
    }
}
