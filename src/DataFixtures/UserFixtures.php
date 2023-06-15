<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function sprintf;

final class UserFixtures extends Fixture
{
    use FakerTrait;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private string $uploadsDir
    ) {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $users = [];
        $genres = ['male', 'female'];
        $genre = $this->faker()->randomElement($genres);

        $filename = sprintf('%s.jpg', Uuid::v4());
        copy(
            sprintf('%s/default_photo.jpg', $this->uploadsDir),
            sprintf('%s/%s', $this->uploadsDir, $filename)
        );

        // User ADMIN
        $admin = (new User());
        $admin
            ->setId(2)
            ->setRoles([User::ADMIN])
            ->setAvatar($filename)
            ->setNickname('Admin')
            ->setEmail('admin@yourdomain.com')
            ->setLastname('Wade')
            ->setFirstname('Warren')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            //->setLastLogin(new \DateTimeImmutable()
            //->setLastLoginIp($this->faker()->ipv4()
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Founder, VP')
        ;

        $manager->persist($admin);

        // User EDITOR
        $editor = (new User());
        $editor
            ->setId(3)
            ->setRoles([User::EDITOR])
            ->setAvatar($filename)
            ->setNickname('Editor')
            ->setEmail('editor@yourdomain.com')
            ->setLastname('Jane')
            ->setFirstname('Cooper')
            ->setPassword($this->hasher->hashPassword($editor, 'editor'))
            //->setLastLogin(new \DateTimeImmutable()
            //->setLastLoginIp($this->faker()->ipv4()
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Editor Staff')
        ;

        $manager->persist($editor);


        for ($usr = 1; $usr <= 16; ++$usr) {
            $filename = sprintf('%s.jpg', Uuid::v4());
            copy(
                sprintf('%s/default_photo.jpg', $this->uploadsDir),
                sprintf('%s/%s', $this->uploadsDir, $filename)
            );

            $user = new User();
            $user->setRoles([User::DEFAULT]);
            $user->setAvatar($filename);
            $user->setNickname($this->faker()->unique()->userName());
            $user->setEmail($this->faker()->unique()->email());
            $user->setLastname($this->faker()->lastName());
            $user->setFirstname($this->faker()->firstName($genre));
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            //$user->setLastLogin(new \DateTimeImmutable());
            //$user->setLastLoginIp($this->faker()->ipv4());

            $manager->persist($user);
            $users[] = $user;

            $this->addReference('user-' . $usr, $user);
        }

        $manager->flush();
    }
}
