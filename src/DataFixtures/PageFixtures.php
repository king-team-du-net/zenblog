<?php

namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

final class PageFixtures extends Fixture
{
    use FakerTrait;

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var string $content */
        $content = $this->faker()->paragraphs(8, true);

        $pages = [
            1 => [
                'title' => 'Terms of service',
                'slug' => 'terms-of-service',
                'content' => $content,
            ],
            2 => [
                'title' => 'Privacy policy',
                'slug' => 'privacy-policy',
                'content' => $content,
            ],
            3 => [
                'title' => 'Cookie policy',
                'slug' => 'cookie-policy',
                'content' => $content,
            ],
            4 => [
                'title' => 'GDPR compliance',
                'slug' => 'gdpr-compliance',
                'content' => $content,
            ],
            5 => [
                'title' => 'Pricing and fees',
                'slug' => 'pricing-and-fees',
                'content' => $content,
            ],
            6 => [
                'title' => 'About us',
                'slug' => 'about-us',
                'content' => $content,
            ],
        ];

        foreach ($pages as $key => $value) {
            $page = (new Page());
            $page
                ->setTitle($value['title'])
                ->setSlug($value['slug'])
                ->setContent($value['content'])
            ;
            $manager->persist($page);
        }

        $manager->flush();
    }
}
