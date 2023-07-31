<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\AdCategory;
use App\Entity\Image\Image;
use App\Entity\Image\Video;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class AdFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(
        private readonly SluggerInterface $slugger,
        private string $uploadsDirAd
    ) {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, AdCategory> $categories */
        $categories = $manager->getRepository(AdCategory::class)->findBy(['isHidden' => false], ['createdAt' => 'DESC']);

        /** @var array<array-key, User> $authors */
        $authors = $manager->getRepository(User::class)->findBy(['registrationToken' => null]);

        $index = 1;

        foreach ($categories as $adCategory) {
            foreach ($authors as $author) {
                for ($a = 1; $a <= 5; ++$a) {
                    //$states = ['draft', 'reviewed', 'rejected', 'published'];

                    /** @var string $content */
                    $content = $this->getContentMarkdown();

                    /** @var string $excerpt */
                    $excerpt = $this->getExcerptText();

                    [$cover, $image1, $image2, $image3] = array_map(
                        function (): string {
                            $filename = sprintf('%s.png', Uuid::v4());
                            copy(
                                sprintf('%s/image.png', $this->uploadsDirAd),
                                sprintf('%s/%s', $this->uploadsDirAd, $filename)
                            );

                            return $filename;
                        },
                        array_fill(0, 4, ''),
                    );

                    $ad = new Ad();
                    $ad->setTitle($this->faker()->unique()->sentence(2, true));
                    $ad->setExcerpt($excerpt);
                    $ad->setContent($content);
                    $ad->setAuthor($author);

                    $ad->setReference(sprintf('REF_%d', $index));
                    $ad->setPrice(rand(40, 1500));
                    $ad->setRooms(rand(1, 5));

                    /*
                    $ad->setState($states[$index % 4]);
                    if ('published' === $ad->getState()) {
                        $ad->setPublishedAt(new \DateTime());
                        $ad->setIsPublished(true);
                        $ad->setIsPortfolio(true);
                        $ad->setIsFeatured(true);
                        $ad->setEnablereviews(true);
                        $ad->setViews(rand(10, 160));
                    }
                    */

                    $ad->setIsPublished($this->faker()->randomElement([true, false]));
                    $ad->setIsPortfolio($this->faker()->randomElement([true, false]));
                    $ad->setIsFeatured($this->faker()->randomElement([true, false]));
                    $ad->setViews(rand(10, 160));
                    $ad->setEnablereviews($this->faker()->randomElement([true, false]));
                    $ad->setTags($this->faker()->unique()->words(3, true));
                    $ad->setAdCategory($adCategory);

                    // Contact And Social Media
                    $ad->setFacebookUrl($this->faker()->url());
                    $ad->setTwitterUrl($this->faker()->url());
                    $ad->setYoutubeurl($this->faker()->url());
                    $ad->setInstagramUrl($this->faker()->url());
                    $ad->setGoogleplusUrl($this->faker()->url());
                    $ad->setLinkedinUrl($this->faker()->url());
                    $ad->setPhonenumber($this->faker()->phoneNumber());
                    $ad->setExternallink(null);
                    $ad->setEmail($this->faker()->email());

                    $ad->setCover($cover);
                    $ad->addMedia(
                        (new Image())
                            ->setFilename($image1)
                            ->setAlt(sprintf('Ad %d', $index))
                    );
                    $ad->addMedia(
                        (new Image())
                            ->setFilename($image2)
                            ->setAlt(sprintf('Ad %d', $index))
                    );
                    $ad->addMedia(
                        (new Image())
                            ->setFilename($image3)
                            ->setAlt(sprintf('Ad %d', $index))
                    );
                    $ad->addMedia(
                        (new Video())
                            ->setUrl('https://www.youtube.com/watch?v=ScMzIvxBSi4')
                    );
                    $ad->addMedia(
                        (new Video())
                            ->setUrl('https://www.dailymotion.com/video/x26p65s')
                    );
                    $ad->addMedia(
                        (new Video())
                            ->setUrl('https://vimeo.com/63655754')
                    );

                    $reflectionProperty = new \ReflectionProperty(Ad::class, 'createdAt');
                    $reflectionProperty->setValue($ad, new \DateTime('2023-06-29 00:00:00'));

                    // Reservation management
                    for ($b = 1; $b <= mt_rand(0, 10); $b++) {
                        $booking = new Booking();

                        $createdAt = $this->faker()->dateTimeBetween('-6 months');
                        $startDate = $this->faker()->dateTimeBetween('-3 months');

                        // End date management
                        $duration = mt_rand(3, 10);
                        $endDate = (clone $startDate)->modify("+$duration days");

                        $amount = $ad->getPrice() * $duration;
                        $booker = $authors[mt_rand(0, \count($authors) - 1)];
                        $comment = $this->faker()->paragraph();

                        $booking
                            ->setBooker($booker)
                            ->setAd($ad)
                            ->setAmount($amount)
                            ->setComment($comment)
                            ->setCreatedAt($createdAt)
                            ->setStartDate($startDate)
                            ->setEndDate($endDate)
                        ;

                        $manager->persist($booking);

                        // Comments management
                        foreach (range(1, 5) as $i) {
                            $states = ['draft', 'reviewed', 'rejected', 'published'];

                            $comment = new Comment();
                            $comment->setAuthor($author);
                            $comment->setContent($this->getExcerptText(random_int(255, 512)));

                            $comment->setState($states[$index % 4]);
                            if ('published' === $comment->getState()) {
                                $comment->setPublishedAt(new \DateTime('now + '.$i.'seconds'));
                                $comment->setIsApproved(true);
                            }

                            $comment->setParent(null);
                            $comment->setRating(random_int(1, 5));
                            $comment->setIp($this->faker()->ipv4);
                            $comment->setIsRGPD(true);
                            $comment->setAd($ad);

                            $manager->persist($comment);
                        }
                    }

                    $manager->persist($ad);

                    ++$index;
                }
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    private function getPhrases(): array
    {
        return [
            'Rorem ipsum dolor sit amet consectetur adipiscing elit',
            'Tellentesque vitae velit ex',
            'Nauris dapibus risus quis suscipit vulputate',
            'Pros diam egestas libero eu vulputate risus',
            'On hac habitasse platea dictumst',
            'Norbi tempus commodo mattis',
            'Ot suscipit posuere justo at vulputate',
            'It eleifend mauris et risus ultrices egestas',
            'Iliquam sodales odio id eleifend tristique',
            'Orna nisl sollicitudin id varius orci quam id turpis',
            'Mulla porta lobortis ligula vel egestas',
            'Ourabitur aliquam euismod dolor non ornare',
            'Ied varius a risus eget aliquam',
            'Munc viverra elit ac laoreet suscipit',
            'Bellentesque et sapien pulvinar consectetur',
            'Vbi est barbatus nix',
            'Cbnobas sunt hilotaes de placidus vita',
            'Zbi est audax amicitia',
            'Aposs sunt solems de superbus fortis',
            'Dae humani generis',
            'Eiatrias tolerare tanquam noster caesium',
            'Reres talis saepe tractare de camerarius flavum sensorem',
            'Tilva de secundus galatae demitto quadra',
            'Yunt accentores vitare salvus flavum parses',
            'Motus sensim ad ferox abnoba',
            'Nunt seculaes transferre talis camerarius fluctuies',
            'Hra brevis ratione est',
            'Dunt torquises imitari velox mirabilis medicinaes',
            'Bineralis persuadere omnes finises desiderium',
            'Massus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getExcerptText(int $maxLength = 255): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        do {
            $text = u('. ')->join($phrases)->append('.');
            array_pop($phrases);
        } while ($text->length() > $maxLength);

        return $text;
    }

    private function getContentMarkdown(): string
    {
        return <<<'MARKDOWN'
            Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
            incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
            deserunt mollit anim id est laborum.

              * Ut enim ad minim veniam
              * Quis nostrud exercitation *ullamco laboris*
              * Nisi ut aliquip ex ea commodo consequat

            Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
            nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
            Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
            himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
            luctus dolor.

            Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
            ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
            Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
            efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
            nulla vitae est.

            Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
            sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
            arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
            ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
            tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
            Sed in egestas erat.

            Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
            lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
            congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
            tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
            tincidunt, faucibus nisl in, aliquet libero.
            MARKDOWN;
    }
    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            //AdCategoryFixtures::class,
        ];
    }
}
