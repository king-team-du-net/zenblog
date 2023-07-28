<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class Normalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    abstract public function normalize(mixed $object, string $format = null, array $context = []): array;

    abstract public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool;

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
