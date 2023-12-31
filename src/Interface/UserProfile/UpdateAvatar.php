<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\UserProfile;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

final class UpdateAvatar implements UpdateAvatarInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $uploadsDirUser
    ) {
    }

    public function __invoke(User $user): void
    {
        if (null !== $user->getAvatar()) {
            $filesystem = new Filesystem();
            $filesystem->remove(\sprintf('%s/%s', $this->uploadsDirUser, $user->getAvatar()));
        }

        /** @var UploadedFile $avatarFile */
        $avatarFile = $user->getAvatarFile();

        $user->setAvatar(\sprintf('%s.%s', Uuid::v4(), $avatarFile->guessClientExtension()));

        $avatarFile->move($this->uploadsDirUser, $user->getAvatar());

        $this->em->flush();
    }
}
