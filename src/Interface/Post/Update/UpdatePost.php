<?php

declare(strict_types=1);

namespace App\Interface\Post\Update;

use App\Entity\Post;
use App\Entity\Image\Image;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UpdatePost implements UpdatePostInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $uploadsDirPost,
    ) {
    }

    public function __invoke(Post $post): void
    {
        /** @var ?UploadedFile $coverFile */
        $coverFile = $post->getCoverFile();

        if (null !== $coverFile) {
            $post->setCover(sprintf('%s.%s', Uuid::v4(), $coverFile->guessClientExtension()));
            $coverFile->move($this->uploadsDirPost, $post->getCover());
        }

        foreach ($post->getMedias() as $media) {
            if ($media instanceof Image) {
                $imageFile = $media->getFile();

                if (null !== $imageFile) {
                    $media->setFilename(sprintf('%s.%s', Uuid::v4(), $imageFile->guessClientExtension()));
                    $imageFile->move($this->uploadsDirPost, $media->getFilename());
                }
            }
        }

        $this->em->flush();
    }
}
