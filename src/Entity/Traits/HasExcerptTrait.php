<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasExcerptTrait
{
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 10)
    ]
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
