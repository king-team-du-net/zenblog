<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Repository\PageRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'sitemap', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    public function sitemap(
        Request $request,
        PostRepository $postRepository,
        PageRepository $pageRepository
    ): Response {
        $hostname = $request->getSchemeAndHttpHost();

        // Register static pages urls
        $urls = [];
        $urls[] = ['loc' => $this->generateUrl('homepage')];
        $urls[] = ['loc' => $this->generateUrl('blog')];
        $urls[] = ['loc' => $this->generateUrl('contact')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('auth_login')];
        $urls[] = ['loc' => $this->generateUrl('auth_registration')];
        $urls[] = ['loc' => $this->generateUrl('auth_reset_password_request')];

        // dd($urls);

        // Register pages urls
        foreach ($pageRepository->findAll() as $page) {
            $urls[] = [
                'loc' => $this->generateUrl('page', ['slug' => $page->getSlug()]),
                'lastmod' => $page->getCreatedAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        // Register blog posts urls
        $posts = $postRepository->findBy(['hidden' => true], ['publishedAt' => 'DESC']);
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $this->generateUrl('blog_article', ['slug' => $post->getSlug()]),
                'lastmod' => $post->getUpdatedAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        $response = new Response(
            $this->renderView(
                'frontend/pages/sitemap.html.twig',
                compact('urls', 'hostname'),
                200
            )
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
