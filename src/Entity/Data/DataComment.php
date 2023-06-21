<?php

namespace App\Entity\Data;

use App\Entity\Post;
use App\Entity\Comment;

abstract class DataComment
{
    public ?int $id = null;

    public ?int $userId = null;

    public ?string $avatar = null;

    public ?string $nickname = null;

    public ?string $email = null;

    public string $content = '';

    //public ?int $target = null;

    public ?Post $post = null;

    public int $createdAt = 0;

    public ?int $parent = 0;

    public ?Comment $entity = null;
}
