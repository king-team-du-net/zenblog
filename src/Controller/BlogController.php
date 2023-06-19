<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostSharedType;
use App\Service\SendMailService;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use App\Interface\Post\PostSharedInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    //#[Route('/', name: 'blog_index', methods: [Request::METHOD_GET])]
    /*public function blogIndex(
        Request $request, 
        PostRepository $postRepository, 
        CategoryRepository $categoryRepository, 
        PaginatorInterface $paginator
    ): Response {
        $categories = $categoryRepository->findAll();

        $page = $request->query->getInt('page', 1);
        $query = $postRepository->queryAllBlog();
        $posts = $paginator->paginate(
            $query,
            $page,
            Post::NUM_ITEMS_PER_PAGE
        );

        if ($posts->count() === 0) {
            throw new NotFoundHttpException($this->translator->trans('No Found Articles'));
        }

        return $this->render('blog/index.html.twig', [
            'categories' => $categories,
            'posts' => $posts
        ]);
    }*/

    #[Route('/', name: 'blog_index', defaults: ['page' => '1', '_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Route('/rss.xml', name: 'blog_rss', defaults: ['page' => '1', '_format' => 'xml'], methods: [Request::METHOD_GET])]
    #[Route('/page/{page<[1-9]\d{0,8}>}', name: 'blog_index_paginated', defaults: ['_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
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
            $tag = $tagRepository->findOneBy(['name' => $request->query->get('tag')]);
        }

        $posts = $postRepository->findAllPost($page, $tag);
        $categories = $categoryRepository->findCategoryCount();

        return $this->render('blog/index.'.$_format.'.twig', [
            'categories' => $categories,
            'pagination' => $posts,
            'tagName' => $tag?->getName(),
        ]);
    }

    #[Route('/post/{slug}', name: 'blog_show', methods: [Request::METHOD_GET])]
    public function blogShow(Post $post): Response
    {
        return $this->render('blog/show.html.twig', compact('post'));
    }

    #[Route(path: '/post/{slug}/share', name: 'blog_show_share', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function blogShowShare(Request $request, Post $post, SendMailService $mail): Response
    {
        if (!$post) {
            $this->addFlash('danger', $this->translator->trans('The blog post not be found'));
            return $this->redirectToRoute('blog_index');
        }

        $form = $this->createForm(PostSharedType::class)->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $subject = sprintf('%s recommends you to read "%s"', $data['sender_name'], $post->getTitle());
                $mail->send(
                    $data['receiver_email'],
                    $subject,
                    'blog-show-shared',
                    [
                        'post' => $post,
                        'sender_name' => $data['sender_name'],
                        'sender_comments' => $data['sender_comments'],
                    ],
                );
                $this->addFlash('success', $this->translator->trans('🚀 Post successfully shared with your friend!'));
                return $this->redirectToRoute('blog_index');
            } else {
                $this->addFlash('danger', $this->translator->trans('The form contains invalid data'));
            }

            return $this->redirectToRoute('blog_show_share', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/show-shared.html.twig', compact('post', 'form'));
    }

    #[Route('/search', name: 'blog_search', methods: [Request::METHOD_GET])]
    public function blogSearch(Request $request): Response
    {
        return $this->render('blog/search.html.twig', ['query' => (string) $request->query->get('q', '')]);
    }
}
