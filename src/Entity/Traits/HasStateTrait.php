<?php

namespace App\Entity\Traits;

use App\Entity\Post;
use Doctrine\ORM\Mapping as ORM;

trait HasStateTrait
{
    const STATES = ['DRAFT', 'REVIEWD', 'REJECTED', 'PUBLISHED'];

    #[ORM\Column]
    private string $state = 'draft'; //Post::STATES[0];

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
