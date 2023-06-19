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

        foreach ($tags as $key => $value) {
            $tag = (new Tag());
            $tag
                ->setName($value['name'])
                ->setSlug($value['slug'])
            ;
            $manager->persist($tag);

            //$this->addReference('tag-' . $key, $tag);
        }

        $manager->flush();
    }
}
