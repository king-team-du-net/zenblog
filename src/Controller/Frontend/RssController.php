<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

class RssController extends AbstractController
{
    #[Route(path: '/rss.xml', name: 'rss', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function rss(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findLatestPublished(5);

        $response = new Response(
            $this->renderView(
                'frontend/pages/rss.html.twig',
                compact('posts'),
                200
            )
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
