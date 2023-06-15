<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route(path: '/tag', name: 'tag_index', methods: [Request::METHOD_GET])]
    public function tagIndex(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    #[Route(path: '/tag/{slug}', name: 'tag_show', methods: [Request::METHOD_GET])]
    public function tagShow(Tag $tag, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAllTag($tag);

        return $this->render('tag/show.html.twig', compact('tag', 'posts'));
    }
}
