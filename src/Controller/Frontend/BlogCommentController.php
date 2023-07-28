<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\UX\Turbo\TurboBundle;
use App\Repository\CommentRepository;
use Symfony\Component\Mercure\Update;
use App\Event\Post\CommentCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\Post\Comment\CommentEm;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        HubInterface $hub,
        TranslatorInterface $translator
    ): Response {
        // Find recent comments approved
        $comments = $post->getIsApprovedComments();

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

            /*
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $hub->publish(new Update(
                    "post_{$post->getId()}_comments",
                    $this->renderView('comment/turbo.stream.html.twig', [
                        'comment' => $comment,
                        'commentsCount' => $comments->count() + 1,
                        'commentForm' => $emptyCommentForm,
                    ])
                ));

                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                // $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                // return $this->render('comments/success.stream.html.twig', [
                //     'comment' => $comment,
                //     'commentsCount' => $comments->count() + 1,
                //     'commentForm' => $emptyCommentForm,
                // ]);
            }
            */

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
