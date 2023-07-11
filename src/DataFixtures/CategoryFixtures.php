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
                'icon' => 'fas fa-podcast',
            ],
            2 => [
                'name' => 'Discussions',
                'slug' => 'discussions',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'danger',
                'icon' => 'fab fa-discourse',
            ],
            3 => [
                'name' => 'Astuces',
                'slug' => 'astuces',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'primary',
                'icon' => 'fas fa-exclamation',
            ],
            4 => [
                'name' => 'News',
                'slug' => 'news',
                'hidden' => true,
                'numberOfPosts' => 0,
                'color' => 'info',
                'icon' => 'fas fa-info',
            ],
            5 => [
                'name' => 'Challenges',
                'slug' => 'challenges',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'secondary',
                'icon' => 'fas fa-chalkboard',
            ],
            6 => [
                'name' => 'Développement',
                'slug' => 'developpement',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'info',
                'icon' => 'fab fa-dev',
            ],
            7 => [
                'name' => 'Web Design',
                'slug' => 'web-design',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'warning',
                'icon' => 'fas fa-image',
            ],
            8 => [
                'name' => 'Text Design',
                'slug' => 'text-design',
                'hidden' => false,
                'numberOfPosts' => 0,
                'color' => 'primary',
                'icon' => 'fas fa-keyboard',
            ],
        ];

        foreach ($categories as $key => $value) {
            $category = (new Category());
            $category
                ->setName($value['name'])
                ->setSlug($value['slug'])
                ->setHidden($value['hidden'])
                ->setNumberOfPosts($value['numberOfPosts'])
                ->setColor($value['color'])
            ;
            $manager->persist($category);

            //$this->addReference('category-' . $key, $category);
        }

        $manager->flush();
    }
}
