<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Event\Post\CommentCreatedEvent;
use App\Form\CommentType;
use App\Interface\Post\Comment\CommentEm;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogCommentController extends AbstractController
{
    #[Route('/comment/{slug}/create', name: 'blog_comment_create', methods: [Request::METHOD_POST])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function blogCommentCreate(
        #[CurrentUser] User $user,
        Request $request,
        RequestStack $stack,
        #[MapEntity(mapping: ['slug' => 'slug'])] Post $post,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $em,
        CommentRepository $commentRepository,
        CommentEm $commentEm,
        TranslatorInterface $translator
    ): Response {
        // Create a new comment
        $comment = new Comment();
        $comment->setIp($stack->getMainRequest()?->getClientIp());
        $comment->setAuthor($user);
        $comment->setIsApproved(false);

        $post->addComment($comment);

        $commentForm = $this->createForm(CommentType::class, $comment);
        $emptyCommentForm = clone $commentForm;
        $commentForm->handleRequest($request);

        $parentid = $commentForm->get('parentid')->getData();
        if (null !== $parentid) {
            $parent = $em->getRepository(Comment::class)->find($parentid);
        }

        $comment->setParent($parent ?? null);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            // $comment = $commentForm->getData();
            $commentEm($comment);
            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));
            $this->addFlash('success', $translator->trans('flash_success.comments_successfully'));

            return $this->redirectToRoute('blog_article', ['slug' => $post->getSlug()]);
        }

        return $this->render('frontend/comment/form_error.html.twig', compact('post', 'commentForm'));
    }

    public function blogCommentForm(Post $post): Response
    {
        $commentForm = $this->createForm(CommentType::class);

        return $this->render('frontend/comment/form.html.twig', compact('post', 'commentForm'));
    }
}
