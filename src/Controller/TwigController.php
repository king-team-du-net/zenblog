<?php

namespace App\Controller;

use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TwigController extends AbstractController
{
    #[Route(path: '/header', name: 'header', methods: [Request::METHOD_GET], priority: 10)]
    public function header(Request $request, CategoryRepository $category, int $maxResults = 6): Response
    {
        // Category
        $navbarCategories = $category->findBy(['hidden' => true], ['createdAt' => 'DESC'], $maxResults);

        /** @var Request $request */
        $request = $this->container->get('request');
        $routename = $request->get('_route');
        $routeparams = $request->get('_route_params');

        return $this->render(
            'global/_header.html.twig',
            compact('navbarCategories')
        )->setSharedMaxAge(50);
    }

    #[Route(path: '/sidebar', name: 'sidebar', methods: [Request::METHOD_GET], priority: 10)]
    public function sidebar(PostRepository $post, CategoryRepository $category, TagRepository $tag, int $maxResults = 6): Response
    {
        // Total
        $totalPosts = $post->count([]);

        // Post
        $latestPosts = $post->findBy([], ['publishedAt' => 'DESC'], $maxResults);
        $mostCommentedPosts = $post->findMostCommented($maxResults);

        // Tags
        $latestTags = $tag->findBy([], ['createdAt' => 'DESC'], $maxResults);

        // Category
        $sidebarCategories = $category->findBy(['hidden' => true], ['createdAt' => 'DESC'], $maxResults);

        return $this->render(
            'global/_sidebar.html.twig',
            compact('totalPosts', 'latestPosts', 'mostCommentedPosts', 'latestTags', 'sidebarCategories')
        )->setSharedMaxAge(50);
    }

    #[Route(path: '/footer', name: 'footer', methods: [Request::METHOD_GET], priority: 10)]
    public function footer(): Response
    {
        # code...

        return $this->render(
            'global/_footer.html.twig',
        )->setSharedMaxAge(50);
    }
}
