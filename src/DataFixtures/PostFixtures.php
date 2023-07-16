<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Image\Image;
use App\Entity\Image\Video;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class PostFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(
        private string $uploadsDirPost
    ) {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $administrators */
        $administrators = $manager->getRepository(User::class)->findBy(['id' => 1]);

        /** @var array<array-key, Category> $categories */
        $categories = $manager->getRepository(Category::class)->findAll();

        /** @var array<array-key, Tag> $tags */
        $tags = $manager->getRepository(Tag::class)->findAll();

        foreach ($categories as $category) {
            foreach ($administrators as $administrator) {
                for ($index = 1; $index < 160; ++$index) {
                    $states = ['draft', 'reviewed', 'rejected', 'published'];

                    /** @var string $content */
                    $content = $this->faker()->paragraphs(10, true);

                    /*
                    $filename = sprintf('/%s.jpg', Uuid::v4());
                    copy(
                        sprintf('%s/default.jpg', $this->uploadsDirPost),
                        sprintf('%s/%s', $this->uploadsDirPost, $filename)
                    );
                    */

                    [$cover, $image1, $image2, $image3] = array_map(
                        function (): string {
                            $filename = sprintf('%s.png', Uuid::v4());
                            copy(
                                sprintf('%s/image.png', $this->uploadsDirPost),
                                sprintf('%s/%s', $this->uploadsDirPost, $filename)
                            );

                            return $filename;
                        },
                        array_fill(0, 4, ''),
                    );

                    $post = new Post();
                    $post->setAuthor($administrator);
                    //$post->setImage($this->faker()->image($this->uploadsDirPost, 640, 480, null, false));
                    $post->setTitle($this->faker()->unique()->sentence(4));
                    $post->setContent($content);
                    $post->setExcerpt($this->faker()->realText(500));
                    $post->setReadtime(rand(10, 160));
                    $post->setState($states[$index % 4]);
                    if ('published' === $post->getState()) {
                        $post->setPublishedAt(new \DateTimeImmutable());
                        $post->setHidden(true);
                        $post->setViews(rand(10, 160));
                        $post->setEnablereviews(true);
                    }
                    $post->setCategory($category);

                    shuffle($tags);
                    foreach (array_slice($tags, 0, 2) as $tag) {
                        $post->getTags()->add($tag);
                    }

                    $post->setCover($cover);
                    $post->addMedia(
                        (new Image())
                            ->setFilename($image1)
                            ->setAlt(sprintf('Post %d', $index))
                    );
                    $post->addMedia(
                        (new Image())
                            ->setFilename($image2)
                            ->setAlt(sprintf('Post %d', $index))
                    );
                    $post->addMedia(
                        (new Image())
                            ->setFilename($image3)
                            ->setAlt(sprintf('Post %d', $index))
                    );
                    $post->addMedia(
                        (new Video())
                            ->setUrl('https://www.youtube.com/watch?v=ScMzIvxBSi4')
                    );
                    $post->addMedia(
                        (new Video())
                            ->setUrl('https://www.dailymotion.com/video/x26p65s')
                    );
                    $post->addMedia(
                        (new Video())
                            ->setUrl('https://vimeo.com/63655754')
                    );

                    $reflectionProperty = new \ReflectionProperty(Post::class, 'createdAt');
                    $reflectionProperty->setValue($post, new \DateTime('2023-06-29 00:00:00'));

                    $manager->persist($post);

                    if ($index > 160 - count($tags)) {
                        $tags[$index % count($tags)];
                    }
    
                    if (0 === $index % 100) {
                        $manager->flush();
                    }
                }
            }
        }

        //$manager->flush();
    }

    /**
     * @return array<array-key, class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            TagFixtures::class
        ];
    }
}
