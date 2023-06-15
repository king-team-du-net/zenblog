<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasIsApprovedTrait
{
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\NotNull]
    private bool $isApproved = false;

    public function isIsApproved(): bool
    {
        return $this->isApproved;
    }

    public function getIsApproved(): bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }
}
