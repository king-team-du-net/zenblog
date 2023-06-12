<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($p = 1; $p <= 16; ++$p) {
            $category = $this->getReference('category-' . $this->faker()->numberBetween(1, 8));
            $user = $this->getReference('user-' . $this->faker()->numberBetween(1, 16));

            $post = new Post();
            /** @var User $user */
            $post->setAuthor($user);
            $post->setTitle($this->faker()->unique()->sentence(10));
            $post->setSlug($this->faker()->slug());
            $post->setContent($this->faker()->realText());
            $post->setCreatedAt(new \DateTime($this->faker()->date('Y-m-d\TH:i:s')));
            $post->setOnline($this->faker()->boolean());
            /** @var Category $category */
            $post->setCategory($category);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class
        ];
    }
}
