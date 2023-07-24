<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Data;

use App\Entity\Comment;
use App\Entity\Post;

abstract class DataComment
{
    public ?int $id = null;

    public ?int $userId = null;

    public ?string $avatar = null;

    public ?string $nickname = null;

    public ?string $email = null;

    public string $content = '';

    // public ?int $target = null;

    public ?Post $post = null;

    public int $publishedAt = 0;

    public ?int $parent = 0;

    public ?int $rating = 0;

    public ?Comment $entity = null;
}
