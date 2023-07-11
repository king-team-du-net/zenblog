<?php

namespace App\EventSubscriber;

use Twig\Environment;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigNavbarSidebarFooterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Environment $twig,
        private readonly PostRepository $post,
        private readonly CategoryRepository $category,
        private readonly TagRepository $tag,
        private readonly CacheInterface $cache
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Sidebar

        // Total
        $totalPosts = $this->cache->get('app.totalPosts', function (ItemInterface $item) {
            $item->expiresAfter(20);

            return $this->post->count([]);
        });

        // Post
        $popularPosts = $this->cache->get('app.popularPosts', function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->post->findBy([], ['publishedAt' => 'DESC'], 6);
        });
        $trendingPosts = $this->cache->get('app.trendingPosts', function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->post->findBy([], ['publishedAt' => 'DESC'], 6);
        });
        $latestPosts = $this->cache->get('app.latestPosts', function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->post->findBy([], ['publishedAt' => 'DESC'], 6);
        });

        // Tag
        $latestTags = $this->cache->get('app.latestTags', function (ItemInterface $item) {
            $item->expiresAfter(40);

            return $this->tag->findBy([], ['createdAt' => 'DESC'], 6);
        });

        // Category
        $navbarCategories = $this->cache->get('app.navbarCategories', function (ItemInterface $item) {
            $item->expiresAfter(40);

            return $this->category->findBy(['hidden' => true], ['createdAt' => 'DESC'], 6);
        });
        $sidebarCategories = $this->cache->get('app.sidebarCategories', function (ItemInterface $item) {
            $item->expiresAfter(40);

            return $this->category->findBy(['hidden' => true], ['createdAt' => 'DESC'], 6);
        });

        // Sidebar NavBar
        $this->twig->addGlobal('totalPosts', $totalPosts);
        $this->twig->addGlobal('popularPosts', $popularPosts);
        $this->twig->addGlobal('trendingPosts', $trendingPosts);
        $this->twig->addGlobal('latestPosts', $latestPosts);
        $this->twig->addGlobal('latestTags', $latestTags);
        $this->twig->addGlobal('navbarCategories', $navbarCategories);
        $this->twig->addGlobal('sidebarCategories', $sidebarCategories);
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
