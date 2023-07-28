<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasFeaturedOrderTrait
{
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $featuredorder = null;

    public function getFeaturedorder(): ?int
    {
        return $this->featuredorder;
    }

    public function setFeaturedorder(int $featuredorder): static
    {
        $this->featuredorder = $featuredorder;

        return $this;
    }
}
