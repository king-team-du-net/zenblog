<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Repository\TagRepository;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

final class TwigWidgetExtension extends AbstractExtension
{
    public function __construct(
        private readonly TagRepository $tag,
        private readonly CategoryRepository $category
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('widgetTag', [$this, 'widgetTag']),
            new TwigFunction('widgetCategory', [$this, 'widgetCategory']),
        ];
    }

    public function widgetTag(int $maxResults = 8): array
    {
        return $this->tag->findBy([], ['id' => 'DESC'], $maxResults);
    }

    public function widgetCategory(int $maxResults = 5): array
    {
        return $this->category->findBy([], ['hidden' => 'DESC'], $maxResults);
    }
}
