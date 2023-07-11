<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class TagFixtures extends Fixture
{
    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $tags = [
            1 => [
                'name' => 'Podcasts',
                'slug' => 'podcasts',
                'color' => 'success',
            ],
            2 => [
                'name' => 'Discussions',
                'slug' => 'discussions',
                'color' => 'danger',
            ],
            3 => [
                'name' => 'Astuces',
                'slug' => 'astuces',
                'color' => 'primary',
            ],
            4 => [
                'name' => 'News',
                'slug' => 'news',
                'color' => 'info',
            ],
            5 => [
                'name' => 'Challenges',
                'slug' => 'challenges',
                'color' => 'secondary',
            ],
            6 => [
                'name' => 'Développement',
                'slug' => 'developpement',
                'color' => 'info',
            ],
            7 => [
                'name' => 'Web Design',
                'slug' => 'web-design',
                'color' => 'warning',
            ],
            8 => [
                'name' => 'Text Design',
                'slug' => 'text-design',
                'color' => 'primary',
            ],
        ];

        foreach ($tags as $key => $value) {
            $tag = (new Tag());
            $tag
                ->setName($value['name'])
                ->setSlug($value['slug'])
                ->setColor($value['color'])
            ;
            $manager->persist($tag);

            //$this->addReference('tag-' . $key, $tag);
        }

        $manager->flush();
    }
}
