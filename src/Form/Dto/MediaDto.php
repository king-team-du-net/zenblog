<?php

declare(strict_types=1);

namespace App\Form\Dto;

use App\Entity\Image\Image;
use App\Entity\Image\Media;
use App\Entity\Image\Video;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class MediaDto
{
    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';

    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::TYPE_IMAGE, self::TYPE_VIDEO])]
    public string $type;

    public ?string $filename = null;

    public ?UploadedFile $file = null;

    public ?string $alt = null;

    public ?string $url = null;

    public ?Media $original = null;

    private static function create(
        string $type,
        ?string $filename = null,
        ?UploadedFile $file = null,
        ?string $alt = null,
        ?string $url = null,
        ?Media $original = null
    ): MediaDto {
        $media = new self();
        $media->original = $original;
        $media->type = $type;
        $media->filename = $filename;
        $media->file = $file;
        $media->alt = $alt;
        $media->url = $url;

        return $media;
    }

    public static function fromEntity(Video|Image|Media $media): self
    {
        /* @phpstan-ignore-next-line */
        return match ($media::class) {
            Image::class => self::create(
                type: self::TYPE_IMAGE,
                filename: $media->getFilename(),
                alt: $media->getAlt(),
                original: $media
            ),
            Video::class => self::create(
                type: self::TYPE_VIDEO,
                url: $media->getUrl(),
                original: $media
            ),
        };
    }

    public function toEntity(): Media
    {
        if (self::TYPE_IMAGE === $this->type) {
            $image = $this->original instanceof Image ? $this->original : new Image();

            $image->setFilename(null === $this->filename ? '' : $this->filename);

            /** @var UploadedFile $file */
            $file = $this->file;
            $image->setFile($file);

            /** @var string $alt */
            $alt = $this->alt;
            $image->setAlt($alt);

            return $image;
        }

        $video = $this->original instanceof Video ? $this->original : new Video();

        /** @var string $url */
        $url = $this->url;
        $video->setUrl($url);

        return $video;
    }
}
