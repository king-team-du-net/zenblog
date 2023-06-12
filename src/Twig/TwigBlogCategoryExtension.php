<?php

namespace App\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

final class TwigBlogCategoryExtension extends AbstractExtension
{
    public function __construct(private readonly CategoryRepository $repository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('blogCategory', [$this, 'blogCategory']),
        ];
    }

    public function blogCategory(): array
    {
        return $this->repository->findAll();
    }
}
