<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form\DataTransformer;

use App\Entity\Image\Media;
use App\Form\Dto\MediaDto;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements DataTransformerInterface<Media, MediaDto>
 */
final class MediaTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?MediaDto
    {
        if (!$value instanceof Media) {
            return null;
        }

        return MediaDto::fromEntity($value);
    }

    public function reverseTransform(mixed $value): ?Media
    {
        if (!$value instanceof MediaDto) {
            return null; // @codeCoverageIgnore
        }

        return $value->toEntity();
    }
}
