<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Twig\TwigSettingExtension;
use App\Repository\CommentRepository;
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
    #[Route(path: '/{category}', name: 'blog', defaults: ['page' => '1', '_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Route(path: '/rss.xml', name: 'blog_rss', defaults: ['page' => '1', '_format' => 'xml'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function blog(
        Request $request, 
        PaginatorInterface $paginator, 
        TwigSettingExtension $setting,
        TranslatorInterface $translator,
        string $_format,
        $category = "all"
    ): Response {
        /*
        $category = "all";

        $categorySlug = $request->query->get('category') == "" ? "all" : $request->query->get('category');
        if ($categorySlug != "all") {
            $category = $setting->getPostCategories(["slug" => $categorySlug])->getQuery()->getOneOrNullresult();
            if (!$category) {
                $this->addFlash('danger', $translator->trans('flash_danger.blog_post_category'));
                return $this->redirectToRoute('blog');
            }
        }
        */

        $keyword = ($request->query->get('keyword')) == "" ? "all" : $request->query->get('keyword');
        $posts = $paginator->paginate(
            $setting->getPosts(["category" => $category, "keyword" => $keyword])->getQuery(), 
            $request->query->getInt('page', 1), Post::PAGE_SIZE, 
            ['wrap-queries' => true]
        );

        return $this->render('post/blog.'.$_format.'.twig', compact('posts'));
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
            return $this->redirectToRoute('blog');
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

    #[Route(path: '/blog-categories', name: 'blog_categories', methods: [Request::METHOD_GET])]
    public function blogCategories(): Response
    {
        return $this->render('post/blog-categories.html.twig');
    }
}
