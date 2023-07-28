<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\DataFixtures;

use App\Entity\AppLayoutSettings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

final class AppLayoutSettingFixtures extends Fixture
{
    use FakerTrait;

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        $layouts = [
            1 => [

                // Logo
                'logo_name' => '5f626cc22a186068458664.png',
                'logo_size' => 3964,
                'logo_mime_type' => 'image/png',
                'logo_original_name' => 'logo.png',
                'logo_dimensions' => '200,50',

                // Favicon
                'favicon_name' => '5ecac8821172a412596921.png',
                'favicon_size' => 2200,
                'favicon_mime_type' => 'image/png',
                'favicon_original_name' => 'favicon-32x32.png',
                'favicon_dimensions' => '32,32',

                // OG
                'og_image_name' => '5faadc546e235285098877.jpg',
                'og_image_size' => 57754,
                'og_image_mime_type' => 'image/jpeg',
                'og_image_original_name' => 'ogImage.jpg',
                'og_image_dimensions' => '1200,630',

            ],
        ];

        foreach ($layouts as $key => $value) {
            $layout = new AppLayoutSettings();

            // Logo
            $layout->setLogoName($value['logo_name']);
            $layout->setLogoSize($value['logo_size']);
            $layout->setLogoMimeType($value['logo_mime_type']);
            $layout->setLogoOriginalName($value['logo_original_name']);
            $layout->setLogoDimensions([$value['logo_dimensions']]);

            // Favicon
            $layout->setFaviconName($value['favicon_name']);
            $layout->setFaviconSize($value['favicon_size']);
            $layout->setFaviconMimeType($value['favicon_mime_type']);
            $layout->setFaviconOriginalName($value['favicon_original_name']);
            $layout->setFaviconDimensions([$value['favicon_dimensions']]);

            // OG
            $layout->setOgImageName($value['og_image_name']);
            $layout->setOgImageSize($value['og_image_size']);
            $layout->setOgImageMimeType($value['og_image_mime_type']);
            $layout->setOgImageOriginalName($value['og_image_original_name']);
            $layout->setOgImageDimensions([$value['og_image_dimensions']]);

            $manager->persist($layout);
        }

        $manager->flush();
    }
}
