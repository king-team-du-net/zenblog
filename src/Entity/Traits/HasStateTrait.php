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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasStateTrait
{
    #[ORM\Column(type: Types::STRING)]
    private string $state = 'draft';

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
