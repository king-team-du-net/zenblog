<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\DataTransformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

final class TagsTransformer implements DataTransformerInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @param Collection<int, Tag> $value
     */
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        return implode(',', $value->map(static fn (Tag $tag): string => $tag->getName())->toArray());
    }

    /**
     * @param string $value
     *
     * @return Collection<int, Tag>
     */
    public function reverseTransform(mixed $value): Collection
    {
        $tags = u($value)->split(',');
        array_walk($tags, static fn (string & $tagName): string => u($tagName)->trim()->toString());

        $tagsCollection = new ArrayCollection();
        $tagsRepository = $this->em->getRepository(Tag::class);

        foreach ($tags as $tagName) {
            if ('' === $tagName) {
                continue;
            }

            $tag = $tagsRepository->findOneBy(['name' => $tagName]);

            if (null === $tag) {
                $tag = new Tag();
                /* @var string $tagName */
                $tag->setName($tagName);
                $this->em->persist($tag);
            }

            $tagsCollection->add($tag);
        }

        return $tagsCollection;
    }
}
