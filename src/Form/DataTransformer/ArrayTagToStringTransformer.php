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

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

/**
 * See https://symfony.com/doc/current/form/data_transformers.html.
 *
 * This data transformer is used to translate the array of tags into a comma separated format
 * that can be displayed and managed by Bootstrap-tagsinput js plugin (and back on submit).
 *
 * @template-implements DataTransformerInterface<Tag[], string>
 */
final class ArrayTagToStringTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly TagRepository $tags
    ) {
    }

    public function transform($tags): string
    {
        return implode(',', $tags);
    }

    /**
     * @phpstan-param string|null $string
     *
     * @phpstan-return array<int, Tag>
     */
    public function reverseTransform($string): array
    {
        if (null === $string || u($string)->isEmpty()) {
            return [];
        }

        $names = array_filter(array_unique($this->trim(u($string)->split(','))));

        // Get the current tags and find the new ones that should be created.
        /** @var Tag[] $tags */
        $tags = $this->tags->findBy([
            'name' => $names,
        ]);

        $newNames = array_diff($names, $tags);
        foreach ($newNames as $name) {
            $tags[] = new Tag($name);

            // There's no need to persist these new tags because Doctrine does that automatically
            // thanks to the cascade={"persist"} option in the App\Entity\Post::$tags property.
        }

        // Return an array of tags to transform them back into a Doctrine Collection.
        // See Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::reverseTransform()
        return $tags;
    }

    /**
     * @param string[] $strings
     *
     * @return string[]
     */
    private function trim(array $strings): array
    {
        $result = [];

        foreach ($strings as $string) {
            $result[] = trim($string);
        }

        return $result;
    }
}
