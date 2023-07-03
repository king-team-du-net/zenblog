<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Post> $posts */
        $posts = $manager->getRepository(Post::class)->findAll();

        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findBy(['registrationToken' => null]);

        $index = 1;

        foreach ($posts as $post) {
            foreach ($users as $user) {
                /** @var string $content */
                $content = $this->faker()->paragraphs(3, true);

                $manager->persist(
                    (new Comment())
                        ->setAuthor($user)
                        ->setPost($post)
                        ->setContent($content)
                        ->setParent(null)
                        ->setRating(rand(1, 5))
                        ->setIp($this->faker()->ipv4)
                        ->setIsApproved($this->faker()->randomElement([true, false]))
                );

                ++$index;
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [PostFixtures::class];
    }
}
