<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class CategoryFixtures extends Fixture
{
    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $categories = [
            1 => [
                'name' => 'Podcasts',
                'slug' => 'podcasts',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'success',
            ],
            2 => [
                'name' => 'Discussions',
                'slug' => 'discussions',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'danger',
            ],
            3 => [
                'name' => 'Astuces',
                'slug' => 'astuces',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'primary',
            ],
            4 => [
                'name' => 'News',
                'slug' => 'news',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'secondary',
            ],
            5 => [
                'name' => 'Challenges',
                'slug' => 'challenges',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'dark',
            ],
            6 => [
                'name' => 'Développement',
                'slug' => 'developpement',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'info',
            ],
            7 => [
                'name' => 'Web Design',
                'slug' => 'web-design',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'light',
            ],
            8 => [
                'name' => 'Text Design',
                'slug' => 'text-design',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'default',
            ],
        ];

        foreach ($categories as $key => $value) {
            $category = (new Category());
            $category
                ->setName($value['name'])
                ->setSlug($value['slug'])
                ->setHidden($value['hidden'])
                ->setNumberOfPosts($value['numberOfPosts'])
            ;
            $manager->persist($category);

            //$this->addReference('category-' . $key, $category);
        }

        $manager->flush();
    }
}
