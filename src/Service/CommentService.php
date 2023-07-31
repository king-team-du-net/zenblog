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

use App\Entity\Ad;
use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Twig\TwigSettingExtension;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CommentService
{
    public function __construct(
        private readonly TwigSettingExtension $setting,
        private readonly EntityManagerInterface $em,
        private readonly NormalizerInterface $normalizer,
        private readonly CommentRepository $commentRepository,
        private readonly PaginatorInterface $paginator,
        private readonly Security $security,
        private readonly RequestStack $stack
    ) {
    }

    public function getPaginatedComments(Post $post = null): PaginationInterface
    {
        $request = $this->stack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        /** @var int $limit */
        $limit = $this->setting->getSetting('blog_comments_per_page');
        // $limit = Comment::COMMENT_LIMIT;

        $commentsQuery = $this->commentRepository->findForPagination($post);

        return $this->paginator->paginate($commentsQuery, $page, $limit);
    }

    public function addComment(
        Comment $comment,
        FormInterface $form,
        Post $post = null,
        Ad $ad = null
    ): void {
        $comment->setIp($this->stack->getMainRequest()?->getClientIp());
        $comment->setPost($post);
        $comment->setAd($ad);
        $comment->setIsApproved(false);
        $comment->setIsRGPD(true);
        $comment->setPublishedAt(new \DateTime('now'));

        $parentid = $form->get('parentid')->getData();

        if (null !== $parentid) {
            $parent = $this->em->getRepository(Comment::class)->find($parentid);
        }

        $comment->setParent($parent ?? null);

        $this->em->persist($comment);
        $this->em->flush();
    }

    public function deletePreliminaryChecks(?Comment $comment): ?JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([
                'code' => 'NOT_AUTHENTICATED',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$comment) {
            return new JsonResponse([
                'code' => 'COMMENT_NOT_FOUND',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($this->security->getUser() !== $comment->getAuthor()) {
            return new JsonResponse([
                'code' => 'UNAUTHORIZED',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return null;
    }

    public function updateComment(Comment $comment, string $content): void
    {
        $comment->setContent($content);
        $this->em->flush();
    }

    public function deleteComment(Comment $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function normalizeComment(Comment $comment): array
    {
        return $this->normalizer->normalize($comment, context: [
            'groups' => 'comment',
        ]);
    }
}
