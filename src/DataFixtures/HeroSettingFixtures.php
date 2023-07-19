<?php

namespace App\DataFixtures;

use App\Entity\HomepageHeroSettings;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class HeroSettingFixtures extends Fixture
{
    use FakerTrait;

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $homepages = [
            1 => [
                'title' => 'Post',
                'paragraph' => 'Online Post Management And Categories',
                'content' => 'custom',

                /*
                'custom_background_name' => '5d99d60e41207545475471.jpg',
                'custom_background_size' => 346806,
                'custom_background_mime_type' => 'image/jpeg',
                'custom_background_original_name' => 'post.jpg',
                'custom_background_dimensions' => '1500,1000',
                */
                'show_search_box' => 1,
            ],
        ];

        foreach ($homepages as $key => $value) {
            $homepage = new HomepageHeroSettings();
            $homepage->setTitle($value['title']);
            $homepage->setParagraph($value['paragraph']);
            $homepage->setContent($value['content']);

            /*
            $homepage->setCustomBackgroundName($value['custom_background_name']);
            $homepage->setCustomBackgroundSize($value['custom_background_size']);
            $homepage->setCustomBackgroundMimeType($value['custom_background_mime_type']);
            $homepage->setCustomBackgroundOriginalName($value['custom_background_original_name']);
            $homepage->setCustomBackgroundDimensions([$value['custom_background_dimensions']]);
            */

            $homepage->setShowSearchBox((bool) $value['show_search_box']);
            $manager->persist($homepage);
        }

        $manager->flush();
    }
}
