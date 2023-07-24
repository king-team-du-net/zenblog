<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image\Image;
use App\Entity\Image\Video;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;
use function Symfony\Component\String\u;

final class PostsTagsCategoryCommentAdminFixtures extends Fixture
{
    use FakerTrait;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly SluggerInterface $slugger,
        private string $uploadsDirUser,
        private string $uploadsDirPost
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadTeams($manager);
        $this->loadUsers($manager);
        $this->loadCategories($manager);
        $this->loadTags($manager);
        $this->loadPosts($manager);
    }

    private function loadTeams(ObjectManager $manager): void
    {
        $filename = sprintf('/%s.png', Uuid::v4());
        copy(
            sprintf('%s/default.png', $this->uploadsDirUser),
            sprintf('%s/%s', $this->uploadsDirUser, $filename)
        );

        foreach ($this->getTeamData() as [$firstname, $lastname, $nickname, $slug, $password, $email, $designation, $roles]) {
            $team = new User();
            $team->setFirstname($firstname);
            $team->setLastname($lastname);
            $team->setNickname($nickname);
            $team->setSlug($slug);
            $team->setPassword($this->hasher->hashPassword($team, $password));
            $team->setEmail($email);
            $team->setRoles($roles);
            $team->setAvatar($filename);
            $team->setTeam(true);
            $team->setIsVerified(true);
            $team->setLastLogin(new \DateTimeImmutable());
            $team->setLastLoginIp($this->faker()->ipv4());
            $team->setYoutubeurl($this->faker()->url());
            $team->setExternallink($this->faker()->url());
            $team->setPhonenumber($this->faker()->phoneNumber());
            $team->setTwitterUrl($this->faker()->url());
            $team->setInstagramUrl($this->faker()->url());
            $team->setFacebookUrl($this->faker()->url());
            $team->setGoogleplusUrl($this->faker()->url());
            $team->setLinkedinUrl($this->faker()->url());
            $team->setAbout($this->faker()->realText(254));
            $team->setRegistrationToken(null);
            $team->setDesignation($designation);

            $manager->persist($team);
            $this->addReference($nickname, $team);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $filename = sprintf('/%s.png', Uuid::v4());
        copy(
            sprintf('%s/default.png', $this->uploadsDirUser),
            sprintf('%s/%s', $this->uploadsDirUser, $filename)
        );

        foreach ($this->getUserData() as [$firstname, $lastname, $nickname, $slug, $password, $email, $roles]) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setNickname($nickname);
            $user->setSlug($slug);
            $user->setPassword($this->hasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setAvatar($filename);
            $user->setTeam(false);
            $user->setIsVerified(true);
            $user->setLastLogin(new \DateTimeImmutable());
            $user->setLastLoginIp($this->faker()->ipv4());
            $user->setYoutubeurl($this->faker()->url());
            $user->setExternallink($this->faker()->url());
            $user->setPhonenumber($this->faker()->phoneNumber());
            $user->setTwitterUrl($this->faker()->url());
            $user->setInstagramUrl($this->faker()->url());
            $user->setFacebookUrl($this->faker()->url());
            $user->setGoogleplusUrl($this->faker()->url());
            $user->setLinkedinUrl($this->faker()->url());
            $user->setAbout(null);
            $user->setRegistrationToken(null);
            $user->setDesignation(null);

            $manager->persist($user);
            $this->addReference($nickname, $user);
        }

        $manager->flush();
    }

    public function loadCategories(ObjectManager $manager): void
    {
        $categories = [
            1 => [
                'name' => 'Podcasts',
                'slug' => 'podcasts',
                'hidden' => true,
                'numberOfPosts' => 10,
                'color' => 'success',
                'icon' => 'fas fa-podcast',
            ],
            2 => [
                'name' => 'Discussions',
                'slug' => 'discussions',
                'hidden' => true,
                'numberOfPosts' => 10,
                'color' => 'danger',
                'icon' => 'fab fa-discourse',
            ],
            3 => [
                'name' => 'Astuces',
                'slug' => 'astuces',
                'hidden' => true,
                'numberOfPosts' => 10,
                'color' => 'primary',
                'icon' => 'fas fa-exclamation',
            ],
            4 => [
                'name' => 'News',
                'slug' => 'news',
                'hidden' => true,
                'numberOfPosts' => 10,
                'color' => 'info',
                'icon' => 'fas fa-info',
            ],
            5 => [
                'name' => 'Challenges',
                'slug' => 'challenges',
                'hidden' => false,
                'numberOfPosts' => 10,
                'color' => 'secondary',
                'icon' => 'fas fa-chalkboard',
            ],
            6 => [
                'name' => 'Développement',
                'slug' => 'developpement',
                'hidden' => false,
                'numberOfPosts' => 10,
                'color' => 'info',
                'icon' => 'fab fa-dev',
            ],
            7 => [
                'name' => 'Web Design',
                'slug' => 'web-design',
                'hidden' => false,
                'numberOfPosts' => 10,
                'color' => 'warning',
                'icon' => 'fas fa-image',
            ],
            8 => [
                'name' => 'Text Design',
                'slug' => 'text-design',
                'hidden' => false,
                'numberOfPosts' => 10,
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
                ->setIcon($value['icon'])
            ;
            $manager->persist($category);

        }

        $manager->flush();
    }

    private function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagData() as $name) {
            $tag = new Tag($name);

            $manager->persist($tag);
            $this->addReference('tag-'.$name, $tag);
        }

        $manager->flush();
    }

    private function loadPosts(ObjectManager $manager): void
    {
        /** @var array<array-key, Category> $categories */
        $categories = $manager->getRepository(Category::class)->findAll();

        $index = 1;
        foreach ($categories as $category) {
            foreach ($this->getPostData() as [$title, $slug, $excerpt, $content, $publishedAt, $author, $tags]) {
                $states = ['draft', 'reviewed', 'rejected', 'published'];

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
                $post->setTitle($title);
                $post->setSlug($slug);
                $post->setExcerpt($excerpt);
                $post->setContent($content);
                //$post->setPublishedAt($publishedAt);
                $post->setAuthor($author);
                $post->addTag(...$tags);

                $post->setReadtime(rand(10, 160));
                $post->setState($states[$index % 4]);
                if ('published' === $post->getState()) {
                    $post->setPublishedAt($publishedAt);
                    $post->setHidden(true);
                    $post->setViews(rand(10, 160));
                    $post->setEnablereviews(true);
                }
                $post->setCategory($category);

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

                foreach (range(1, 5) as $i) {
                    /** @var User $commentAuthor */
                    $commentAuthor = $this->getReference(['john-doe', 'bob-doe', 'jane-doe'][0 === $i ? 0 : random_int(0, 1)]);

                    $comment = new Comment();
                    $comment->setAuthor($commentAuthor);
                    $comment->setContent($this->getExcerptText(random_int(255, 512)));
                    $comment->setPublishedAt(new \DateTime('now + '.$i.'seconds'));
                    $comment->setParent(null);
                    $comment->setRating(random_int(1, 5));
                    $comment->setIp($this->faker()->ipv4);
                    $comment->setIsRGPD(['john-doe', 'bob-doe', 'jane-doe'][0 === $i ? 0 : random_int(0, 1)]);
                    $comment->setIsApproved($this->faker()->randomElement([true, false]));

                    $post->addComment($comment);
                }

                $manager->persist($post);

                ++$index;
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array{string, string, string, string, string, string, string, array<string>}>
     */
    private function getTeamData(): array
    {
        return [
            // $teamData = [$firstname, $lastname, $nickname, $slug, $password, $email, $designation, $roles];
            ['Williamson', 'Cameron', 'administrator', 'administrator', 'administrator', 'administrator@yourdomain.com', 'Founder & CEO', [User::ADMINISTRATOR]],
            ['Warren', 'Wade', 'admin', 'admin', 'admin', 'admin@yourdomain.com', 'Founder, VP', [User::ADMIN]],
            ['Cooper', 'Jane', 'editor', 'editor', 'editor', 'editor@yourdomain.com', 'Editor Staff', [User::EDITOR]],
        ];
    }

    /**
     * @return array<array{string, string, string, string, string, string, array<string>}>
     */
    private function getUserData(): array
    {
        return [
            // $teamData = [$firstname, $lastname, $nickname, $slug, $password, $email, $roles];
            ['John', 'Doe', 'john-doe', 'john-doe', 'password', 'john-doe@yourdomain.com', [User::DEFAULT]],
            ['Jane', 'Doe', 'jane-doe', 'jane-doe', 'password', 'jane-doe@yourdomain.com', [User::DEFAULT]],
            ['Bob', 'Doe', 'bob-doe', 'bob-doe', 'password', 'bob-doe@yourdomain.com', [User::DEFAULT]],
        ];
    }

    /**
     * @return string[]
     */
    private function getTagData(): array
    {
        return [
            'podcasts',
            'discussions',
            'astuces',
            'news',
            'challenges',
            'developpement',
            'web-design',
            'text-design',
        ];
    }

    /**
     * @return array<int, array{0: string, 1: string, 2: bool, 3: int, 4: string}>
     */
    private function getCategoryData(): array
    {
        return [
            // $categoryData = [$name, $slug, $color, $hidden, $numberOfPosts, $icon];
            ['Podcasts', 'podcasts', 'success', true, 10, 'fas fa-podcast'],
            ['Discussions', 'discussions', 'danger', true, 10, 'fab fa-discourse'],
            ['Astuces', 'astuces', 'primary', true, 10, 'fas fa-exclamation'],
            ['News', 'news', 'info', true, 10, 'fas fa-info'],
            ['Challenges', 'challenges', 'secondary', false, 0, 'fas fa-chalkboard'],
            ['Developpement', 'developpement', 'info', false, 0, 'fab fa-dev'],
            ['Web Design', 'web-design', 'warning', false, 0, 'fas fa-image'],
            ['Text Design', 'text-design', 'primary', false, 0, 'fas fa-keyboard'],
        ];
    }

    /**
     * @throws \Exception
     *
     * @return array<int, array{0: string, 1: AbstractUnicodeString, 2: string, 3: string, 4: \DateTime, 5: User, 6: array<Tag>}>
     */
    private function getPostData(): array
    {
        $posts = [];
        foreach ($this->getPhrases() as $i => $title) {
            // $postData = [$title, $slug, $excerpt, $content, $publishedAt, $author, $tags, $comments, $category];

            /** @var User $user */
            $user = $this->getReference(['administrator', 'admin', 'editor'][0 === $i ? 0 : random_int(0, 1)]);

            $posts[] = [
                $title,
                $this->slugger->slug($title)->lower(),
                $this->getExcerptText(),
                $this->getContentMarkdown(),
                (new \DateTime('now - '.$i.'days'))->setTime(random_int(8, 17), random_int(7, 49), random_int(0, 59)),
                // Ensure that the first post is written by Williamson Cameron to simplify tests
                $user,
                $this->getRandomTags(),
                //$this->getRandomCategories(),
            ];
        }

        return $posts;
    }

    /**
     * @return string[]
     */
    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
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
     * @throws \Exception
     *
     * @return array<Tag>
     */
    private function getRandomTags(): array
    {
        $tagNames = $this->getTagData();
        shuffle($tagNames);
        $selectedTags = \array_slice($tagNames, 0, random_int(2, 4));

        return array_map(function ($tagName) {
            /** @var Tag $tag */
            $tag = $this->getReference('tag-'.$tagName);

            return $tag;
        }, $selectedTags);
    }

    /**
     * @throws \Exception
     *
     * @return array<Category>
     */
    private function getRandomCategories(): array
    {
        $categoryNames = $this->getTagData();
        shuffle($categoryNames);
        $selectedCategories = \array_slice($categoryNames, 0, random_int(1, 2));

        return array_map(function ($categoryName) {
            /** @var Category $category */
            $category = $this->getReference('category-'.$categoryName);

            return $category;
        }, $selectedCategories);
    }
}
