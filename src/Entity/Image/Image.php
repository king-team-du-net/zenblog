<?php

declare(strict_types=1);

namespace App\Entity\Image;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Image extends Media
{
    #[ORM\Column]
    private string $filename;

    #[ImageConstraint]
    #[Assert\NotNull(groups: ['image'])]
    private ?UploadedFile $file = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private string $alt;

    public function type(): string
    {
        return 'image';
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): Image
    {
        $this->file = $file;

        return $this;
    }
}
