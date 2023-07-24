<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use App\Entity\Post;
use Doctrine\ORM\Mapping as ORM;

trait HasStateTrait
{
    public const STATES = ['DRAFT', 'REVIEWD', 'REJECTED', 'PUBLISHED'];

    #[ORM\Column]
    private string $state = 'draft'; // Post::STATES[0];

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
