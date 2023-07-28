<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Post\Comment;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

final class CommentEm implements CommentEmInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(Comment $comment): void
    {
        $this->em->persist($comment);
        $this->em->flush();
    }
}
