<?php

namespace App\Interface\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

final class TagArrayToStringTransformer implements DataTransformerInterface
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
        }

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
