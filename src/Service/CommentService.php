<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class CommentService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $stack
    ) {
    }

    public function createComment(
        Comment $comment,
        FormInterface $form,
        Post $post = null
    ): void {
        $comment->setIp($this->stack->getMainRequest()?->getClientIp());
        $comment->setPost($post);
        $comment->setIsApproved(false);
        $comment->setCreatedAt(new \DateTime('now'));

        $parentid = $form->get("parentid")->getData();

        if ($parentid != null) {
            $parent = $this->em->getRepository(Comment::class)->find($parentid);
        }

        $comment->setParent($parent ?? null);

        $this->em->persist($comment);
        $this->em->flush();
    }

    public function updateComment(Comment $comment, string $content): Comment
    {
        $comment->setContent($content);
        $this->em->flush();

        return $comment;
    }

    public function deleteComment(Comment $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }
}
