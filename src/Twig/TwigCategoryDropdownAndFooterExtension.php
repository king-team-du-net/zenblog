<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Twig;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class TwigCategoryDropdownAndFooterExtension extends AbstractExtension
{
    public function __construct(
        private readonly CategoryRepository $repository,
        private readonly RouterInterface $router,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categoryDropdown', [$this, 'categoryDropdown']),
            new TwigFunction('categoryFooter', [$this, 'categoryFooter']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('categoriesToString', [$this, 'categoriesToString']),
        ];
    }

    public function categoryDropdown(int $maxResults = 8): array
    {
        return $this->repository->findBy(['isHidden' => true], ['createdAt' => 'DESC'], $maxResults);
    }

    public function categoryFooter(int $maxResults = 4): array
    {
        return $this->repository->findBy(['isHidden' => true], ['createdAt' => 'DESC'], $maxResults);
    }

    public function categoriesToString(Collection $categories): string
    {
        $generateCategoryLink = function (Category $category) {
            $url = $this->router->generate('category_show', [
                'slug' => $category->getSlug(),
            ]);

            return "<a href='$url' class='text-decoration-none' style='color: {$category->getColor()}'>{$category->getName()}</a>";
        };

        $categoryLinks = array_map($generateCategoryLink, $categories->toArray());

        return implode(', ', $categoryLinks);
    }
}
