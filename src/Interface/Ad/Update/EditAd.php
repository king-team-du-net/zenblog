<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Ad\Update;

use App\Entity\Image\Image;
use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

final class EditAd implements EditAdInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $uploadsDirAd,
    ) {
    }

    public function __invoke(Ad $ad): void
    {
        /** @var ?UploadedFile $coverFile */
        $coverFile = $ad->getCoverFile();

        if (null !== $coverFile) {
            $ad->setCover(sprintf('%s.%s', Uuid::v4(), $coverFile->guessClientExtension()));
            $coverFile->move($this->uploadsDirAd, $ad->getCover());
        }

        foreach ($ad->getMedias() as $media) {
            if ($media instanceof Image) {
                $imageFile = $media->getFile();

                if (null !== $imageFile) {
                    $media->setFilename(sprintf('%s.%s', Uuid::v4(), $imageFile->guessClientExtension()));
                    $imageFile->move($this->uploadsDirAd, $media->getFilename());
                }
            }
        }

        $this->em->flush();
    }
}
