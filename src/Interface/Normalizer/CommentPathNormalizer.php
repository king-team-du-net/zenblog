<?php

declare(strict_types=1);

namespace App\Interface\Normalizer;

use App\Entity\Post;
use App\Entity\Comment;
use App\Interface\Encoder\Encoder;

class CommentPathNormalizer extends Normalizer
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        $path = [];

        if ($object instanceof Comment) {
            $post = $object->getPost();

            if ($post instanceof Post) {
                $path = (new PostPathNormalizer())->normalize($post, $format, $context);
            }

            $path['hash'] = "c{$object->getId()}";

            return $path;
        }

        throw new \RuntimeException("Can't normalize path");
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return ($data instanceof Comment)
            && Encoder::FORMAT === $format;
    }
}
