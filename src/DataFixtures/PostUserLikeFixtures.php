<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class PostUserLikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $users */
        $users = $this->userRepository->findBy([], ['registrationToken' => null]);

        /** @var array<array-key, Post> $posts */
        $posts = $this->postRepository->findBy([], ['publishedAt' => 'DESC']);

        /*
        foreach ($posts as $post) {
            for ($index = 0; $index < mt_rand(0, 15); ++$index) {
                $post->addLike(
                    $users[mt_rand(0, count($users) - 1)]
                );
            }
        }
        */

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
        ];
    }
}
