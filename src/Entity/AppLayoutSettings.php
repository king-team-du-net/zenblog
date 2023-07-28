<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity;

use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\AppLayoutSettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Entity(repositoryClass: AppLayoutSettingsRepository::class)]
#[ORM\Table(name: 'app_layout_setting')]
#[Vich\Uploadable]
class AppLayoutSettings
{
    use HasIdTrait;
    use HasTimestampTrait;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'app_layout', fileNameProperty: 'logoName', size: 'logoSize', mimeType: 'logoMimeType', originalName: 'logoOriginalName', dimensions: 'logoDimensions')]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'],
        mimeTypesMessage: 'The file should be an image'
    )]
    private ?File $logoFile = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $logoName = null;

    #[Column(type: Types::INTEGER, nullable: true)]
    private ?int $logoSize = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $logoMimeType = null;

    #[Column(type: Types::STRING, length: 1000, nullable: true)]
    private ?string $logoOriginalName = null;

    #[Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $logoDimensions = [];

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'app_layout', fileNameProperty: 'faviconName', size: 'faviconSize', mimeType: 'faviconMimeType', originalName: 'faviconOriginalName', dimensions: 'faviconDimensions')]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'image/x-icon'],
        mimeTypesMessage: 'The file should be an image'
    )]
    private ?File $faviconFile = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $faviconName = null;

    #[Column(type: Types::INTEGER, nullable: true)]
    private ?int $faviconSize = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $faviconMimeType = null;

    #[Column(type: Types::STRING, length: 1000, nullable: true)]
    private ?string $faviconOriginalName = null;

    #[Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $faviconDimensions = [];

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'app_layout', fileNameProperty: 'ogImageName', size: 'ogImageSize', mimeType: 'ogImageMimeType', originalName: 'ogImageOriginalName', dimensions: 'ogImageDimensions')]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'image/x-icon'],
        mimeTypesMessage: 'The file should be an image'
    )]
    private ?File $ogImageFile = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $ogImageName = null;

    #[Column(type: Types::INTEGER, nullable: true)]
    private ?int $ogImageSize = null;

    #[Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $ogImageMimeType = null;

    #[Column(type: Types::STRING, length: 1000, nullable: true)]
    private ?string $ogImageOriginalName = null;

    #[Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $ogImageDimensions = [];

    public function __construct()
    {
    }

    public function setLogoFile(File|UploadedFile|null $logoFile)
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): static
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }

    public function setLogoSize(?int $logoSize): static
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    public function getLogoMimeType(): ?string
    {
        return $this->logoMimeType;
    }

    public function setLogoMimeType(?string $logoMimeType): static
    {
        $this->logoMimeType = $logoMimeType;

        return $this;
    }

    public function getLogoOriginalName(): ?string
    {
        return $this->logoOriginalName;
    }

    public function setLogoOriginalName(?string $logoOriginalName): static
    {
        $this->logoOriginalName = $logoOriginalName;

        return $this;
    }

    public function getLogoDimensions(): array
    {
        return $this->logoDimensions;
    }

    public function setLogoDimensions(?array $logoDimensions): static
    {
        $this->logoDimensions = $logoDimensions;

        return $this;
    }

    public function getLogoPath(): string
    {
        return 'uploads/layout/'.$this->logoName;
    }

    public function setFaviconFile(File|UploadedFile|null $faviconFile)
    {
        $this->faviconFile = $faviconFile;

        if (null !== $faviconFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    public function getFaviconFile(): ?File
    {
        return $this->faviconFile;
    }

    public function getFaviconName(): ?string
    {
        return $this->faviconName;
    }

    public function setFaviconName(?string $faviconName): static
    {
        $this->faviconName = $faviconName;

        return $this;
    }

    public function getFaviconSize(): ?int
    {
        return $this->faviconSize;
    }

    public function setFaviconSize(?int $faviconSize): static
    {
        $this->faviconSize = $faviconSize;

        return $this;
    }

    public function getFaviconMimeType(): ?string
    {
        return $this->faviconMimeType;
    }

    public function setFaviconMimeType(?string $faviconMimeType): static
    {
        $this->faviconMimeType = $faviconMimeType;

        return $this;
    }

    public function getFaviconOriginalName(): ?string
    {
        return $this->faviconOriginalName;
    }

    public function setFaviconOriginalName(?string $faviconOriginalName): static
    {
        $this->faviconOriginalName = $faviconOriginalName;

        return $this;
    }

    public function getFaviconDimensions(): array
    {
        return $this->faviconDimensions;
    }

    public function setFaviconDimensions(?array $faviconDimensions): static
    {
        $this->faviconDimensions = $faviconDimensions;

        return $this;
    }

    public function getFaviconPath(): string
    {
        return 'uploads/layout/'.$this->faviconName;
    }

    public function setOgImageFile(File|UploadedFile|null $ogImageFile)
    {
        $this->ogImageFile = $ogImageFile;

        if (null !== $ogImageFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    public function getOgImageFile(): ?File
    {
        return $this->ogImageFile;
    }

    public function getOgImageName(): ?string
    {
        return $this->ogImageName;
    }

    public function setOgImageName(?string $ogImageName): static
    {
        $this->ogImageName = $ogImageName;

        return $this;
    }

    public function getOgImageSize(): ?int
    {
        return $this->ogImageSize;
    }

    public function setOgImageSize(?int $ogImageSize): static
    {
        $this->ogImageSize = $ogImageSize;

        return $this;
    }

    public function getOgImageMimeType(): ?string
    {
        return $this->ogImageMimeType;
    }

    public function setOgImageMimeType(?string $ogImageMimeType): static
    {
        $this->ogImageMimeType = $ogImageMimeType;

        return $this;
    }

    public function getOgImageOriginalName(): ?string
    {
        return $this->ogImageOriginalName;
    }

    public function setOgImageOriginalName(?string $ogImageOriginalName): static
    {
        $this->ogImageOriginalName = $ogImageOriginalName;

        return $this;
    }

    public function getOgImageDimensions(): array
    {
        return $this->ogImageDimensions;
    }

    public function setOgImageDimensions(?array $ogImageDimensions): static
    {
        $this->ogImageDimensions = $ogImageDimensions;

        return $this;
    }

    public function getOgImagePath(): string
    {
        return 'uploads/layout/'.$this->ogImageName;
    }
}
