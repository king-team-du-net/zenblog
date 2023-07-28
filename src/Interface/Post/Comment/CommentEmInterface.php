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

interface CommentEmInterface
{
    public function __invoke(Comment $comment): void;
}
