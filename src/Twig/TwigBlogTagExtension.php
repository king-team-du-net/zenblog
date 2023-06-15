<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Repository\TagRepository;
use Twig\Extension\AbstractExtension;

final class TwigBlogTagExtension extends AbstractExtension
{
    public function __construct(private readonly TagRepository $repository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('blogTag', [$this, 'blogTag']),
        ];
    }

    public function blogTag(): array
    {
        return $this->repository->findAll();
    }
}
