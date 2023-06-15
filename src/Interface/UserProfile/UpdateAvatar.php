<?php

declare(strict_types=1);

namespace App\Interface\UserProfile;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

use function sprintf;

final class UpdateAvatar implements UpdateAvatarInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly string $uploadsDir
    ) {
    }

    public function __invoke(User $user): void
    {
        if (null !== $user->getAvatar()) {
            $filesystem = new Filesystem();
            $filesystem->remove(sprintf('%s/%s', $this->uploadsDir, $user->getAvatar()));
        }

        /** @var UploadedFile $avatarFile */
        $avatarFile = $user->getAvatarFile();

        $user->setAvatar(sprintf('%s.%s', Uuid::v4(), $avatarFile->guessClientExtension()));

        $avatarFile->move($this->uploadsDir, $user->getAvatar());

        $this->em->flush();
    }
}
