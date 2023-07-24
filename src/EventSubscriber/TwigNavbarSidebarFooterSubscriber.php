<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\EventSubscriber;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;

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

        // Post Tag
        $PostTags = $this->cache->get('app.PostTags', function (ItemInterface $item) {
            $item->expiresAfter(40);

            return $this->tag->findBy([], ['id' => 'DESC'], 6);
        });

        // Post Category
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
        $this->twig->addGlobal('PostTags', $PostTags);
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
