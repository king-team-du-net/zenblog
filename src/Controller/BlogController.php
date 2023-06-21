<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\PostSharedType;
use App\Service\SendMailService;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use App\Event\Post\CommentCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
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
    public function blogShow(Post $post, PostRepository $postRepository, EntityManagerInterface $em): Response
    {
        // Number of views
        $post->viewed();
        $em->persist($post);
        $em->flush();

        // Previous Post
        $previousPost = $postRepository->findPreviousPost($post);

        // Next Post
        $nextPost = $postRepository->findNextPost($post);

        return $this->render('blog/show.html.twig', compact('post', 'previousPost', 'nextPost'));
    }

    #[Route(path: '/post/{slug}/share', name: 'blog_show_share', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function blogShowShare(Request $request, Post $post, SendMailService $mail, TranslatorInterface $translator): Response
    {
        if (!$post) {
            $this->addFlash('danger', $translator->trans('flash_danger.blog_post'));
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
                $this->addFlash('success', $translator->trans('post.share_successfully'));
                return $this->redirectToRoute('blog_index');
            } else {
                $this->addFlash('danger', $translator->trans('post.invalid_data'));
            }

            return $this->redirectToRoute('blog_show_share', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/show-shared.html.twig', compact('post', 'form'));
    }

    #[Route('/comment/{slug}/create', name: 'blog_comment_create', methods: [Request::METHOD_POST])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function blogCommentCreate(
        #[CurrentUser] User $user,
        Request $request,
        RequestStack $stack,
        #[MapEntity(mapping: ['slug' => 'slug'])] Post $post,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $em,
        CommentRepository $categoryRepository,
        TranslatorInterface $translator
    ): Response {
        // Find recent comments
        /** @var Comment $comments */
        $comments = $categoryRepository->findRecentComments($post);

        // Create a new comment
        $comment = new Comment();
        $comment->setIp($stack->getMainRequest()?->getClientIp());
        $comment->setAuthor($user);
        $comment->setIsApproved(false);

        $post->addComment($comment);

        $commentForm = $this->createForm(CommentType::class, $comment);
        $emptyCommentForm = clone $commentForm;
        $commentForm->handleRequest($request);

        $parentid = $commentForm->get("parentid")->getData();
        if ($parentid != null) {
            $parent = $em->getRepository(Comment::class)->find($parentid);
        }

        $comment->setParent($parent ?? null);

        /*if ($commentForm->isSubmitted()) {
            if ($commentForm->isValid()) {
                //$comment = $commentForm->getData();
                $em->persist($comment);
                $em->flush();
                $eventDispatcher->dispatch(new CommentCreatedEvent($comment));
                $this->addFlash('success', $translator->trans('Your comment has been sent, thank you. It will be published after validation by a moderator.'));
            } else {
                $this->addFlash('danger', $translator->trans('The form contains invalid data'));
            }

            return $this->redirectToRoute('blog_show', ['slug' => $post->getSlug()]);
        }*/

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            $em->persist($comment);
            $em->flush();
            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));
            $this->addFlash('success', $translator->trans('flash_success.comments_successfully'));
            return $this->redirectToRoute('blog_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('comment/form_error.html.twig', compact('post', 'commentForm', 'comments'));
    }

    public function blogCommentForm(Post $post): Response
    {
        $commentForm = $this->createForm(CommentType::class);

        return $this->render('comment/form.html.twig', compact('post', 'commentForm'));
    }

    #[Route(path: '/post/sidebar', name: 'blog_sidebar', methods: [Request::METHOD_GET], priority: 10)]
    public function blogSidebar(PostRepository $postRepository, int $maxResults = 6): Response
    {
        $totalPosts = $postRepository->count([]);
        $latestPosts = $postRepository->findBy([], ['publishedAt' => 'DESC'], $maxResults);
        $mostCommentedPosts = $postRepository->findMostCommented($maxResults);

        return $this->render(
            'global/_sidebar.html.twig',
            compact('totalPosts', 'latestPosts', 'mostCommentedPosts')
        )->setSharedMaxAge(50);
    }

    #[Route('/search', name: 'blog_search', methods: [Request::METHOD_GET])]
    public function blogSearch(Request $request): Response
    {
        return $this->render('blog/search.html.twig', ['query' => (string) $request->query->get('q', '')]);
    }
}
