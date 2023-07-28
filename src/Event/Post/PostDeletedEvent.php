<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Event\Post;

use App\Entity\Content;
use App\Event\Content\ContentDeletedEvent;

final class PostDeletedEvent extends ContentDeletedEvent
{
    public function __construct(Content $content)
    {
        parent::__construct($content);
    }
}
