<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Post;

use App\Entity\Post;

interface PostSharedInterface
{
    public function __invoke(Post $post): void;
}
