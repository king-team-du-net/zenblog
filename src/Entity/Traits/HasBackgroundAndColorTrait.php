<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasBackgroundAndColorTrait
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => 'primary'])]
    #[Assert\NotNull]
    private ?string $color = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => 'primary'])]
    #[Assert\NotNull]
    #[Assert\Length(min: 7)]
    private ?string $background = null;

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }
}
