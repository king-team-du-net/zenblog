<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', methods: [Request::METHOD_GET])]
    public function sitemap(Request $request, PostRepository $postRepository): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $urls = [];
        $urls[] = ['loc' => $this->generateUrl('blog_index')];

        $posts = $postRepository->findBy(['hidden' => true], ['publishedAt' => 'DESC']);
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $this->generateUrl('blog_show', ['slug' => $post->getSlug()]),
                'lastmod' => $post->getUpdatedAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        $response = $this->render('pages/sitemap.html.twig', compact('urls', 'hostname'));
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
