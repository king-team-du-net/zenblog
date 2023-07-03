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
        private string $uploadsDirUser
    ) {
    }

    private function createAdministrator(ObjectManager $manager): void
    {
        // User SuperAdmin
        $filename = sprintf('/%s.jpg', Uuid::v4());
        copy(
            sprintf('%s/default.jpg', $this->uploadsDirUser),
            sprintf('%s/%s', $this->uploadsDirUser, $filename)
        );

        $administrator = (new User());
        $administrator
            ->setId(1)
            ->setRoles([User::ADMINISTRATOR])
            ->setIsVerified(true)
            ->setAvatar($filename)
            ->setNickname('administrator')
            ->setSlug('administrator')
            ->setEmail('administrator@yourdomain.com')
            ->setLastname('Cameron')
            ->setFirstname('Williamson')
            ->setPassword($this->hasher->hashPassword($administrator, 'administrator'))
            ->setLastLogin(new \DateTimeImmutable())
            ->setLastLoginIp($this->faker()->ipv4())
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Founder & CEO')
            ->setRegistrationToken(null)
        ;

        $manager->persist($administrator);
    }

    private function createAdmin(ObjectManager $manager): void
    {
        // User Admin
        $filename = sprintf('/%s.jpg', Uuid::v4());
        copy(
            sprintf('%s/default.jpg', $this->uploadsDirUser),
            sprintf('%s/%s', $this->uploadsDirUser, $filename)
        );

        $admin = (new User());
        $admin
            ->setId(2)
            ->setRoles([User::ADMIN])
            ->setIsVerified(true)
            ->setAvatar($filename)
            ->setNickname('admin')
            ->setSlug('admin')
            ->setEmail('admin@yourdomain.com')
            ->setLastname('Wade')
            ->setFirstname('Warren')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setLastLogin(new \DateTimeImmutable())
            ->setLastLoginIp($this->faker()->ipv4())
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Founder, VP')
            ->setRegistrationToken(null)
        ;

        $manager->persist($admin);
    }

    private function createEditor(ObjectManager $manager): void
    {
        // User Editor
        $filename = sprintf('/%s.jpg', Uuid::v4());
        copy(
            sprintf('%s/default.jpg', $this->uploadsDirUser),
            sprintf('%s/%s', $this->uploadsDirUser, $filename)
        );

        $editor = (new User());
        $editor
            ->setId(3)
            ->setRoles([User::EDITOR])
            ->setIsVerified(true)
            ->setAvatar($filename)
            ->setNickname('editor')
            ->setSlug('editor')
            ->setEmail('editor@yourdomain.com')
            ->setLastname('Jane')
            ->setFirstname('Cooper')
            ->setPassword($this->hasher->hashPassword($editor, 'editor'))
            ->setLastLogin(new \DateTimeImmutable())
            ->setLastLoginIp($this->faker()->ipv4())
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Editor Staff')
            ->setRegistrationToken(null)
        ;

        $manager->persist($editor);
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->createAdministrator($manager);
        $manager->flush();
        $this->createAdmin($manager);
        $manager->flush();
        $this->createEditor($manager);
        $manager->flush();

        $users = [];
        $genres = ['male', 'female'];
        $genre = $this->faker()->randomElement($genres);

        for ($usr = 1; $usr <= 15; ++$usr) {
            $filename = sprintf('/%s.jpg', Uuid::v4());
            copy(
                sprintf('%s/default.jpg', $this->uploadsDirUser),
                sprintf('%s/%s', $this->uploadsDirUser, $filename)
            );

            $user = (new User())
                ->setRoles([User::DEFAULT])
                ->setAvatar($filename)
                ->setNickname($this->faker()->unique()->userName())
                ->setEmail($this->faker()->unique()->email())
                ->setLastname($this->faker()->lastName())
                ->setFirstname($this->faker()->firstName($genre))
                ->setLastLogin(new \DateTimeImmutable())
                ->setLastLoginIp($this->faker()->ipv4())
            ;

            if ($usr > 10) {
                $user->setRegistrationToken(Uuid::v4());
                $user->setIsVerified(false);
                $user->setSuspended($this->faker()->randomElement([true, false]));
                $user->setBannedAt(
                    $this->faker()->boolean(75)
                    ? \DateTimeImmutable::createFromMutable(
                        $this->faker()->dateTimeBetween('-50 days', '+10 days')
                    )
                    : null
                );
            } else {
                $user->setRegistrationToken(null);
                $user->setIsVerified(true);
            }

            $manager->persist(
                $user->setPassword(
                    $this->hasher->hashPassword($user, 'password')
                )
            );
            $users[] = $user;

            //$this->addReference('user-' . $usr, $user);
        }

        $manager->flush();
    }
}
