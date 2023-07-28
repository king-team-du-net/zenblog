<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Post\View;

use App\Entity\Post;

interface CreateViewInterface
{
    public function __invoke(Post $post): void;
}
