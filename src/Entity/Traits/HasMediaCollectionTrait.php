<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use App\Entity\Image\Media;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait HasMediaCollectionTrait
{
    /*
    #[ORM\Column(type: Types::STRING)]
    private string $image = '';

    #[Assert\Image(maxSize: '1M', maxRatio: 4/3, minRatio: 4/3)]
    #[Assert\NotNull(groups: ['create'])]
    private ?UploadedFile $imageFile = null;
    */

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['post:read'])]
    private string $cover = '';

    #[Assert\Image(groups: ['cover'], maxSize: '1M', maxRatio: 4 / 3, minRatio: 4 / 3)]
    #[Assert\NotNull(groups: ['cover'])]
    private ?UploadedFile $coverFile = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Media::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    private Collection $medias;

    /*
    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): void
    {
        $this->imageFile = $imageFile;
    }
    */

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCoverFile(): ?UploadedFile
    {
        return $this->coverFile;
    }

    public function setCoverFile(?UploadedFile $coverFile): static
    {
        $this->coverFile = $coverFile;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): static
    {
        $media->setPost($this);
        $this->medias->add($media);

        return $this;
    }

    public function removeMedia(Media $media): static
    {
        $media->setPost(null);
        $this->medias->removeElement($media);

        return $this;
    }
}
