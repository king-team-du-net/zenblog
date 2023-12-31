<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Data\DataComment;
use App\Entity\Post;
use App\Event\Post\CommentCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NewCommentService
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly RequestStack $stack
    ) {
    }

    public function newComment(DataComment $data): Comment
    {
        /** @var Content $target */
        // $target = $this->em->getRepository(Content::class)->find($data->target);

        /** @var Post $post */
        $post = $this->em->getRepository(Post::class)->find($data->post);

        /** @var Comment|null $parent */
        $parent = $data->parent ? $this->em->getReference(Comment::class, $data->parent) : null;

        // Create a new comment
        $comment = (new Comment())
            ->setIp($this->stack->getMainRequest()?->getClientIp())
            ->setAuthor($this->auth->getUserOrNull())
            // ->setNickname($data->nickname)
            ->setPublishedAt(new \DateTime())
            ->setContent($data->content)
            ->setRating($data->rating)
            ->setParent($parent)
            // ->setTarget($target)
            ->setPost($post)
            ->setIsApproved(false)
        ;

        $this->em->persist($comment);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new CommentCreatedEvent($comment));

        return $comment;
    }

    public function updateComment(Comment $comment, string $content): Comment
    {
        $comment->setContent($content);
        $this->em->flush();

        return $comment;
    }

    public function deleteComment(int $commentId): void
    {
        /** @var Comment $comment */
        $comment = $this->em->getReference(Comment::class, $commentId);
        $this->em->remove($comment);
        $this->em->flush();
    }
}
