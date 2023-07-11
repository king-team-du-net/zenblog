<?php

declare(strict_types=1);

namespace App\Interface\Normalizer;

use App\Entity\Post;
use App\Interface\Encoder\Encoder;

class PostPathNormalizer extends Normalizer
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        if ($object instanceof Post) {
            return [
                'path' => 'blog_article',
                'params' => ['slug' => $object->getSlug()],
            ];
        }

        throw new \RuntimeException("Can't normalize path");
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return ($data instanceof Post)
            && Encoder::FORMAT === $format;
    }
}
