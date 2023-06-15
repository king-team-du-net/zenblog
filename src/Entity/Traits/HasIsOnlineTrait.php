<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait HasIsOnlineTrait
{
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $isOnline = false;

    public function isIsOnline(): bool
    {
        return $this->isOnline;
    }

    public function getIsOnline(): bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): static
    {
        $this->isOnline = $isOnline;

        return $this;
    }
}
