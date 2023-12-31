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
use Doctrine\ORM\EntityManagerInterface;

final class CreateView implements CreateViewInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function __invoke(Post $post): void
    {
        // Number of views
        $post->viewed();
        $this->em->persist($post);
        $this->em->flush();
    }
}
