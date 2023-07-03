<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasHiddenTrait
{
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\NotNull]
    private bool $hidden = false;

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): static
    {
        $this->hidden = $hidden;

        return $this;
    }
}
