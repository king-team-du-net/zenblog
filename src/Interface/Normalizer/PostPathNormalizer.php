<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Normalizer;

use App\Entity\Post;
use App\Interface\Encoder\Encoder;

class PostPathNormalizer extends Normalizer
{
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if ($object instanceof Post) {
            return [
                'path' => 'blog_article',
                'params' => ['slug' => $object->getSlug()],
            ];
        }

        throw new \RuntimeException("Can't normalize path");
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return ($data instanceof Post)
            && Encoder::FORMAT === $format;
    }
}
