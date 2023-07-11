<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Repository\CategoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

final class PostCategoryDropdownSubscriber implements EventSubscriberInterface
{
    public const ROUTES = ['homepage', 'blog_index', 'category_index'];

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly Environment $twig
    ) {
    }

    public function listCategories(RequestEvent $event): void
    {
        $route = $event->getRequest()->get('_route');

        if (in_array($route, PostCategoryDropdownSubscriber::ROUTES)) {
            $categories = $this->categoryRepository->findAll();
            $this->twig->addGlobal('allCategories', $categories);
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'listCategories'];
    }
}
