<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Service\PostService;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Twig\TwigSettingExtension;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Interface\Post\View\CreateViewInterface;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

#[Route(path: '/blog')]
class BlogController extends AbstractController
{
    #[Route(path: '/{category}', name: 'blog', methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function blog(Request $request, TagRepository $tagRepository, PostService $postService, $category = "all"): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tagRepository->findOneBy(['slug' => $request->query->get('tag')]);
        }

        $response = $this->render('post/blog.html.twig', [
            'posts' => $postService->getPaginatedPosts(),
            'tagName' => $tag?->getName(),
        ])->setSharedMaxAge(30);

        $response->headers->set(
            AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true'
        );

        return $response;
    }

    #[Route(path: '/blog-article/{slug}', name: 'blog_article', methods: [Request::METHOD_GET])]
    public function blogArticle(
        Post $post,
        TwigSettingExtension $setting,
        PostRepository $postRepository,
        CommentRepository $commentRepository,
        TranslatorInterface $translator,
        CreateViewInterface $createView
    ): Response {
        if (!$post) {
            $this->addFlash('danger', $translator->trans('flash_danger.blog_post'));
            return $this->redirectToRoute('blog');
        }

        // Number of views
        $createView($post);

        /** @var Comment $comments */
        // Find recent comments approved
        $comments = $post->getIsApprovedComments();

        // Similar Posts
        $similars = $postRepository->findSimilarPosts($post, 8);

        // Previous Post
        $previousPost = $postRepository->findPreviousPost($post);

        // Next Post
        $nextPost = $postRepository->findNextPost($post);

        return $this->render('post/blog-article.html.twig', compact('post', 'comments', 'similars', 'previousPost', 'nextPost'));
    }

    #[Route(path: '/blog-categories', name: 'blog_categories', methods: [Request::METHOD_GET])]
    public function blogCategories(): Response
    {
        return $this->render('post/blog-categories.html.twig');
    }
}
