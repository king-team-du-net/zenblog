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

use App\Entity\Category;
use App\Interface\Encoder\Encoder;

class PostCategoryPathNormalizer extends Normalizer
{
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if ($object instanceof Category) {
            return [
                'path' => 'blog_category_show',
                'params' => ['slug' => $object->getSlug()],
            ];
        }

        throw new \RuntimeException("Can't normalize path");
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return ($data instanceof Category)
            && Encoder::FORMAT === $format;
    }
}
