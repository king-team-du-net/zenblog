<?php

namespace App\EventSubscriber;

use Twig\Environment;
use App\Repository\PostRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigSidebarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Environment $twig,
        private readonly PostRepository $postRepository,
        private readonly CacheInterface $cache
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $totalPosts = $this->cache->get('app.totalPosts', function (ItemInterface $item) {
            $item->expiresAfter(20);

            return $this->postRepository->count([]);
        });

        // Sidebar
        $popularPosts = $this->cache->get('app.popularPosts', function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->postRepository->findBy([], ['publishedAt' => 'DESC'], 6);
        });
        $trendingPosts = $this->cache->get('app.trendingPosts', function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->postRepository->findBy([], ['publishedAt' => 'DESC'], 6);
        });
        $latestPosts = $this->cache->get('app.latestPosts', function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->postRepository->findBy([], ['publishedAt' => 'DESC'], 6);
        });

        /*
        $mostCommentedPosts = $this->cache->get('app.mostCommentedPosts', function (ItemInterface $item) {
            $item->expiresAfter(40);

            return $this->postRepository->findMostCommented(5);
        });
        */

        $this->twig->addGlobal('totalPosts', $totalPosts);

        // Sidebar
        $this->twig->addGlobal('popularPosts', $popularPosts);
        $this->twig->addGlobal('trendingPosts', $trendingPosts);
        $this->twig->addGlobal('latestPosts', $latestPosts);

        //$this->twig->addGlobal('mostCommentedPosts', $mostCommentedPosts);
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
