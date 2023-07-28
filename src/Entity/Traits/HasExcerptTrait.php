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
use Meilisearch\Bundle\Searchable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait HasExcerptTrait
{
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?string $excerpt = null;

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }
}
