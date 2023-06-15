<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    #[Route('/', name: 'blog_index', methods: [Request::METHOD_GET])]
    public function blogIndex(
        Request $request,
        PostRepository $postRepository,
        PaginatorInterface $paginator,
        TranslatorInterface $translator
    ): Response {
        $categories = $this->categoryRepository->findAll();

        $page = $request->query->getInt('page', 1);
        $query = $postRepository->queryAllBlog();
        $posts = $paginator->paginate(
            $query,
            $page,
            Post::NUM_ITEMS_PER_PAGE
        );

        if ($posts->count() === 0) {
            throw new NotFoundHttpException($translator->trans('No Found Articles'));
        }

        return $this->render('blog/index.html.twig', [
            'categories' => $categories,
            'posts' => $posts
        ]);
    }

    #[Route('/post/{slug}', name: 'blog_show', methods: [Request::METHOD_GET])]
    public function blogShow(Post $post): Response
    {
        return $this->render('blog/show.html.twig', compact('post'));
    }
}
