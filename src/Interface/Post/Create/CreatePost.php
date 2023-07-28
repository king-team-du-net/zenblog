<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Post\Create;

use App\Entity\Image\Image;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

final class CreatePost implements CreatePostInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $uploadsDirPost,
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function __invoke(Post $post): void
    {
        $post->setSlug((string) $this->slugger->slug($post->getTitle()));

        /** @var UploadedFile $coverFile */
        $coverFile = $post->getCoverFile();
        $post->setCover(sprintf('%s.%s', Uuid::v4(), $coverFile->guessClientExtension()));
        $coverFile->move($this->uploadsDirPost, $post->getCover());

        foreach ($post->getMedias() as $media) {
            if ($media instanceof Image) {
                /** @var UploadedFile $imageFile */
                $imageFile = $media->getFile();
                $media->setFilename(sprintf('%s.%s', Uuid::v4(), $imageFile->guessClientExtension()));
                $imageFile->move($this->uploadsDirPost, $media->getFilename());
            }
        }

        $this->em->persist($post);
        $this->em->flush();
    }
}
