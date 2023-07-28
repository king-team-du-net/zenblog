<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Post\Delete;

use App\Entity\Post;

interface DeletePostInterface
{
    public function __invoke(Post $post): void;
}
