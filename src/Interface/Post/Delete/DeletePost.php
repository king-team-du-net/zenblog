<?php

declare(strict_types=1);

namespace App\Interface\Post\Delete;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

final class DeletePost implements DeletePostInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(Post $post): void
    {
        $this->em->remove($post);
        $this->em->flush();
    }
}
