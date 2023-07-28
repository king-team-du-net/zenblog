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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

trait HasLabelAndSlugAndAssertTrait
{
    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 128)]
    protected ?string $label = null;

    #[ORM\Column(type: Types::STRING, length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['label'], unique: true, updatable: true)]
    protected ?string $slug = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
