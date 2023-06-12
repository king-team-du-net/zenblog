<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    use FakerTrait;

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $users = [];
        $genres = ['male', 'female'];
        $genre = $this->faker()->randomElement($genres);

        for ($usr = 1; $usr <= 16; ++$usr) {
            $user = new User();
            $user->setRoles([User::DEFAULT]);
            $user->setNickname($this->faker()->unique()->userName());
            $user->setEmail($this->faker()->unique()->email());
            $user->setLastname($this->faker()->lastName());
            $user->setFirstname($this->faker()->firstName($genre));
            $user->setPassword($this->hasher->hashPassword($user, 'password'));

            $manager->persist($user);
            $users[] = $user;

            $this->addReference('user-' . $usr, $user);
        }

        $manager->flush();
    }
}
