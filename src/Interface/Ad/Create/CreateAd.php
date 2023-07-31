<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Ad\Create;

use App\Entity\Image\Image;
use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

final class CreateAd implements CreateAdInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $uploadsDirAd,
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function __invoke(Ad $ad): void
    {
        $ad->setSlug((string) $this->slugger->slug($ad->getTitle()));

        /** @var UploadedFile $coverFile */
        $coverFile = $ad->getCoverFile();
        $ad->setCover(sprintf('%s.%s', Uuid::v4(), $coverFile->guessClientExtension()));
        $coverFile->move($this->uploadsDirAd, $ad->getCover());

        foreach ($ad->getMedias() as $media) {
            if ($media instanceof Image) {
                /** @var UploadedFile $imageFile */
                $imageFile = $media->getFile();
                $media->setFilename(sprintf('%s.%s', Uuid::v4(), $imageFile->guessClientExtension()));
                $imageFile->move($this->uploadsDirAd, $media->getFilename());
            }
        }

        $this->em->persist($ad);
        $this->em->flush();
    }
}
