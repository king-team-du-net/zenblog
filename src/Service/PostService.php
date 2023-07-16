<?php

namespace App\Service;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Twig\TwigSettingExtension;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PostService
{
    public function __construct(
        private readonly TwigSettingExtension $setting,
        private readonly PostRepository $postRepository,
        private readonly PaginatorInterface $paginator,
        private readonly RequestStack $stack
    ) {

    }

    public function getPaginatedPosts(?Category $category = null, ?Tag $tag = null): PaginationInterface
    {
        $request = $this->stack->getMainRequest();
        $postsQuery = $this->postRepository->findForPagination($category, $tag);
        $page = $request->query->getInt('page', 1);
        /** @var int $limit */
        $limit = $this->setting->getSetting("blog_posts_per_page");
        //$limit = Post::POST_LIMIT;

        return $this->paginator->paginate($postsQuery, $page, $limit);
    }
}
