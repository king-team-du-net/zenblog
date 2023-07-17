<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RssController extends AbstractController
{
    #[Route(path: '/rss.xml', name: 'rss', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function rss(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findLatestPublished(5);

        $response = new Response(
            $this->renderView(
                'pages/rss.html.twig',
                compact('posts'),
                200
            )
        );
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
