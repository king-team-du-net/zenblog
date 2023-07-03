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

        /** @var string $content */
        $content = '
            <p>
                <span class="firstcharacter">L</span>
                orem ipsum dolor sit, amet consectetur adipisicing elit. 
                Ratione officia sed, suscipit distinctio, numquam omnis quo fuga ipsam quis inventore voluptatum recusandae culpa, 
                unde doloribus saepe labore alias voluptate expedita? Dicta delectus beatae explicabo odio voluptatibus quas, 
                saepe qui aperiam autem obcaecati, illo et! Incidunt voluptas culpa neque repellat sint, accusamus beatae, 
                cumque autem tempore quisquam quam eligendi harum debitis.
            </p>

            <figure class="my-4">
                <img src="../assets/img/post-landscape-1.jpg" alt="" class="img-fluid">
                <figcaption>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, odit? </figcaption>
            </figure>
            <p>
            Sunt reprehenderit, hic vel optio odit est dolore, distinctio iure itaque enim pariatur ducimus. Rerum soluta, 
            perspiciatis voluptatum cupiditate praesentium repellendus quas expedita exercitationem tempora aliquam quaerat in eligendi adipisci harum non omnis reprehenderit quidem beatae modi. Ea fugiat enim libero, ipsam dicta explicabo nihil, tempore, nulla repellendus eos necessitatibus eligendi corporis cum? Eaque harum, eligendi itaque numquam aliquam soluta.</p>
            <p>Explicabo perspiciatis, laborum provident voluptates illum in nulla consectetur atque quaerat excepturi quisquam, veniam velit ex pariatur quos consequuntur? Excepturi reiciendis perferendis, cupiditate dolorem eos illum amet. Beatae voluptates nemo esse ratione voluptate, nesciunt fugit magnam veritatis voluptas dignissimos doloribus maiores? Aliquam, dolores natus exercitationem corrupti blanditiis, consequuntur nihil nobis sed voluptatibus maiores sunt, illo provident aliquid laborum. Vitae, ut.</p>
            <p>Reprehenderit aut sed doloribus blanditiis, aspernatur magni? In molestias rem, similique ut esse repudiandae quod recusandae dolores neque earum omnis at, suscipit fuga? Minima qui veniam deserunt quisquam error amet at ratione nesciunt porro quis placeat repudiandae voluptatibus officiis fuga necessitatibus, expedita officia adipisci eaque labore accusamus? Nesciunt repellat illo exercitationem facilis similique quaerat, quis sequi? Praesentium nulla ipsam dolor.</p>
            <p>Dolorum, incidunt! Adipisci harum itaque maxime dolores doloremque porro eligendi quis, doloribus vel sit rerum sunt obcaecati nam suscipit nulla vitae alias blanditiis aliquam debitis atque illo modi et placeat. Ratione iure eveniet provident. Culpa laboriosam sed ad quia. Corrupti, earum, perferendis dolore cupiditate sint nihil maiores iusto tempora nobis porro itaque est. Ut laborum culpa assumenda pariatur et perferendis?</p>
            <p>Est soluta veritatis laboriosam, consequuntur temporibus asperiores, fugit id a ullam sed, expedita sequi doloribus fugiat. Odio et necessitatibus enim nam, iste reprehenderit cupiditate omnis ut iure aliquid obcaecati, repellendus nemo provident eveniet tempora minus! Voluptates aut laboriosam, maiores nihil accusantium, a dolorum quaerat tenetur illo eum culpa cum laudantium sunt doloremque modi possimus magni? Perferendis cum repudiandae corrupti porro.</p>
            <figure class="my-4">
                <img src="../assets/img/post-landscape-5.jpg" alt="" class="img-fluid">
                <figcaption>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, odit? </figcaption>
            </figure>
            <p>Quis molestiae, dolorem consequuntur labore perferendis enim accusantium commodi optio, sequi magnam ad consectetur iste omnis! Voluptatibus, quia officia esse necessitatibus magnam tempore reprehenderit quo aspernatur! Assumenda, minus dolorem repellendus corporis corrupti quia temporibus repudiandae in. Sit rem aut, consectetur repudiandae perferendis nemo alias, iure ipsam omnis quam soluta, nobis animi quis aliquam blanditiis at. Dicta nemo vero sequi exercitationem.</p>
            <p>Architecto ex id at illum aperiam corporis, quidem magnam doloribus non eligendi delectus laborum porro molestiae beatae eveniet dolor odit optio soluta dolores! Eaque odit a nihil recusandae, error repellendus debitis ex autem ab commodi, maiores officiis doloribus provident optio, architecto assumenda! Nihil cum laboriosam eos dolore aliquid perferendis amet doloremque quibusdam odio soluta vero odit, ipsa, quisquam quod nulla.</p>
            <p>Consequuntur corrupti fugiat quod! Ducimus sequi nemo illo ad necessitatibus amet nobis corporis et quasi. Optio cum neque fuga. Ad excepturi magnam quisquam ex voluptatibus vitae aut nam quidem doloribus, architecto perspiciatis sit consequatur pariatur alias animi expedita quas? Et doloribus voluptatibus perferendis qui fugiat voluptatum autem facere aspernatur quidem quae assumenda iste, sit similique, necessitatibus laborum magni. Ea, dolores!</p>
            <p>Possimus temporibus rerum illo quia repudiandae provident sed quas atque. Ipsam adipisci accusamus iste optio illo aliquam molestias? Voluptatibus, veniam recusandae facilis nobis perspiciatis rem similique, nisi ad explicabo ipsa voluptatum, inventore molestiae natus adipisci? Fuga delectus quia assumenda totam aspernatur. Nobis hic ea rem, quaerat voluptate vero illum laboriosam omnis aspernatur labore, natus ex iusto ducimus exercitationem a officia.</p>
        ';

        $index = 1;

        foreach ($administrators as $administrator) {
            foreach ($categories as $category) {
                for ($i = 1; $i <= 5; ++$i) {
                    //$category = $this->getReference('category-' . $this->faker()->numberBetween(1, 8));
                    //$tag = $this->getReference('tag-' . $this->faker()->numberBetween(1, 8));
                    //$user = $this->getReference('user-' . $this->faker()->numberBetween(1, 16));

                    $states = ['draft', 'reviewed', 'rejected', 'published'];

                    $filename = sprintf('/%s.jpg', Uuid::v4());
                    copy(
                        sprintf('%s/default.jpg', $this->uploadsDirPost),
                        sprintf('%s/%s', $this->uploadsDirPost, $filename)
                    );

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

                    shuffle($tags);

                    $post = new Post();
                    $post->setAuthor($administrator);
                    $post->setImage($this->faker()->image($this->uploadsDirPost, 640, 480, null, false));
                    $post->setTitle($this->faker()->unique()->sentence(10));
                    $post->setContent($content);
                    $post->setExcerpt($this->faker()->realText(500));
                    $post->setReadtime(rand(10, 160));
                    /*
                    $post->setPublishedAt(
                        $this->faker()->boolean(75)
                        ? \DateTimeImmutable::createFromMutable(
                            $this->faker()->dateTimeBetween('-50 days', '+10 days')
                        )
                        : null
                    );
                    $post->setHidden($this->faker()->boolean());
                    */

                    $post->setState($states[$index % 4]);
                    if ('published' === $post->getState()) {
                        $post->setPublishedAt(new \DateTimeImmutable());
                        $post->setHidden(true);
                        $post->setViews(rand(10, 160));
                    }
                    $post->setCategory($category);

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
                    $reflectionProperty->setValue($post, new \DateTimeImmutable('2023-06-29 00:00:00'));

                    $manager->persist($post);

                    // Comment
                    /*for ($com = 1; $com <= rand(1, 5); ++$com) {
                        $comment = new Comment;
                        $comment->setAuthor($user);
                        $comment->setPost($post);
                        $comment->setParent(null);
                        $comment->setIp($this->faker()->ipv4);
                        $comment->setContent($this->faker()->paragraph(3, true));
                        $comment->setIsRGPD(true);
                        $comment->setIsApproved($this->faker()->randomElement([true, false]));
                        $manager->persist($comment);
                    }*/

                    ++$index;
                }
            }
        }

        $manager->flush();
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
