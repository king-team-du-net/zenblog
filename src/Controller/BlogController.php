<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Twig\TwigSettingExtension;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/blog')]
class BlogController extends AbstractController
{
    #[Route(path: '/{category}', name: 'blog_index', defaults: ['page' => '1', '_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Route(path: '/rss.xml', name: 'blog_rss', defaults: ['page' => '1', '_format' => 'xml'], methods: [Request::METHOD_GET])]
    //#[Route(path: '/page/{page<[1-9]\d{0,8}>}', name: 'blog_index_paginated', defaults: ['_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    /*
    public function blogIndex(
        Request $request,
        int $page, 
        string $_format, 
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ): Response {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tagRepository->findOneBy(['slug' => $request->query->get('tag')]);
        }

        //$posts = $postRepository->findAllPost($page, $tag);
        $posts = $postRepository->findRecentPublished($request->query->getInt('page', 1));
        $categories = $categoryRepository->findCategoryCount();

        return $this->render('blog/index.'.$_format.'.twig', [
            'categories' => $categories,
            //'pagination' => $posts,
            'posts' => $posts,
            'tagName' => $tag?->getName(),
        ]);
    }
    */



    public function blog(
        Request $request, 
        PaginatorInterface $paginator, 
        TwigSettingExtension $setting, 
        string $_format, 
        CategoryRepository $categoryRepository, 
        $category = "all"
    ): Response {
        $keyword = ($request->query->get('keyword')) == "" ? "all" : $request->query->get('keyword');
        $posts = $paginator->paginate($setting->getPosts(["category" => $category, "keyword" => $keyword])->getQuery(), $request->query->getInt('page', 1), $setting->getSetting("blog_posts_per_page"), ['wrap-queries' => true]);
        $categories = $categoryRepository->findCategoryCount();

        return $this->render('post/blog.'.$_format.'.twig', compact('posts', 'categories'));
    }

    #[Route(path: '/blog-article/{slug}', name: 'blog_article', methods: [Request::METHOD_GET])]
    public function blogArticle(
        Post $post,
        string $slug,
        TwigSettingExtension $setting,
        PostRepository $postRepository,
        CommentRepository $commentRepository,
        TranslatorInterface $translator,
        EntityManagerInterface $em
    ): Response {
        /** @var Post $post */
        $post = $setting->getPosts(["slug" => $slug])->getQuery()->getOneOrNullResult();

        if (!$post) {
            $this->addFlash('danger', $translator->trans('flash_danger.blog_post'));
            return $this->redirectToRoute('blog_index');
        }

        // Number of views
        $post->viewed();
        $em->persist($post);
        $em->flush();

        // Find recent comments
        /** @var Comment $comments */
        $comments = $commentRepository->findRecentComments($post);

        // Previous Post
        $previousPost = $postRepository->findPreviousPost($post);

        // Next Post
        $nextPost = $postRepository->findNextPost($post);

        return $this->render('post/blog-article.html.twig', compact('post', 'previousPost', 'nextPost', 'comments'));
    }
}
