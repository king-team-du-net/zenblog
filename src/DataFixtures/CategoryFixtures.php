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
            ],
            2 => [
                'name' => 'Discussions',
                'slug' => 'discussions',
            ],
            3 => [
                'name' => 'Astuces',
                'slug' => 'astuces',
            ],
            4 => [
                'name' => 'News',
                'slug' => 'news',
            ],
            5 => [
                'name' => 'Challenges',
                'slug' => 'challenges',
            ],
            6 => [
                'name' => 'Développement',
                'slug' => 'developpement',
            ],
            7 => [
                'name' => 'Web Design',
                'slug' => 'web-design',
            ],
            8 => [
                'name' => 'Text Design',
                'slug' => 'text-design',
            ],
        ];

        foreach ($categories as $key => $value) {
            $category = (new Category());
            $category
                ->setName($value['name'])
                ->setSlug($value['slug'])
            ;
            $manager->persist($category);

            //$this->addReference('category-' . $key, $category);
        }

        $manager->flush();
    }
}
