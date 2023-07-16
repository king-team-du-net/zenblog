<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogRSSController extends AbstractController
{
    #[Route(path: '/rss.xml', name: 'blog_rss', methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function blogRSS(PostRepository $postRepository): Response
    {
        $items = $postRepository->findLatestPublished(5);

        $response = $this->render('post/blog-rss.xml.twig', [
            'items' => $items,
        ]);
        $response->headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

        return $response;
    }
}
