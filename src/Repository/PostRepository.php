<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\HomepageHeroSettings;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Helper\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use function Symfony\Component\String\u;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    final public const PAGE_SIZE = 4;

    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorInterface $paginatorInterface,
        private readonly int $pageSize = self::PAGE_SIZE
    ) {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForPagination(Category $category = null, Tag $tag = null): Query // PostService
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.hidden = true')
            ->andWhere('p.state LIKE :state')
            ->setParameter('state', '%published%')
            ->orderBy('p.publishedAt', 'DESC')
        ;

        if (isset($category)) {
            $qb->leftJoin('p.category', 'c')
                ->where($qb->expr()->eq('c.id', ':id'))
                ->setParameter('id', $category->getId())
            ;
        }

        if (isset($tag)) {
            $qb->leftJoin('p.tags', 't')
                ->where($qb->expr()->eq('t.id', ':id'))
                ->setParameter('id', $tag->getId())
            ;
        }

        return $qb->getQuery();
    }

    public function findPreviousPost(Post $post): ?Post // (BlogController)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.publishedAt < :postPublishedAt')
            ->setParameter('postPublishedAt', $post->getPublishedAt())
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findNextPost(Post $post): ?Post // (BlogController)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.publishedAt > :postPublishedAt')
            ->setParameter('postPublishedAt', $post->getPublishedAt())
            ->orderBy('p.publishedAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Post[] returns an array of Post objects similar with the given post
     */
    public function findSimilarPosts(Post $post, int $limit): array // (BlogController)
    {
        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->addSelect('COUNT(t) AS HIDDEN numberOfTags')
            ->andWhere('t IN (:tags)')
            ->andWhere('p != :post')
            ->setParameters([
                'tags' => $post->getTags(),
                'post' => $post,
            ])
            ->groupBy('p')
            ->addOrderBy('numberOfTags', 'DESC')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Method findMostCommented.
     */
    public function findMostCommented(int $maxResults): array // (TwigController)
    {
        return $this->createQueryBuilder('p')
            ->join('p.comments', 'c')
            ->addSelect('COUNT(c) AS HIDDEN numberOfComments')
            ->andWhere('c.isApproved = true')
            ->groupBy('p')
            ->orderBy('numberOfComments', 'DESC')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Search.
     *
     * @return Post[]
     */
    public function findBySearchedQuery(string $query, int $limit = Paginator::PAGE_SIZE): array // (BlogSearchedController & BlogSearchedComponent)
    {
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('p');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('p.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }

        /** @var Post[] $result */
        $result = $queryBuilder
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    /**
     * Transforms the search string into an array of search terms.
     *
     * @return string[]
     */
    private function extractSearchTerms(string $searchQuery): array // (BlogSearchedController BlogSearchedComponent)
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, static function ($term) {
            return 2 <= $term->length();
        });
    }

    /**
     * Returns number of "Posts" per day.
     *
     * @return void
     */
    public function countByDate()
    {
        // $query = $this->createQueryBuilder('p')
        //     ->select('SUBSTRING(p.publishedAt, 1, 10) as datePosts, COUNT(p) as count')
        //     ->groupBy('datePosts')
        // ;
        // return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(p.publishedAt, 1, 10) as datePosts, COUNT(p) as count FROM App\Entity\Post p GROUP BY datePosts
        ");

        return $query->getResult();
    }

    /**
     * Retrieves the latest posts created by the user.
     *
     * @return Post[] Returns an array of Post objects
     */
    public function findLastByUser(User $user, int $limit): array // (UserController)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.author = :author')
            ->andWhere('p.hidden = true')
            ->andWhere('p.publishedAt <= :now')
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('author', $user)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return QueryBuilder<Post>
     */
    public function findRecent(int $limit): QueryBuilder // (HomePageController)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.hidden = true AND p.publishedAt < NOW()')
            ->andWhere('p.state LIKE :state')
            ->setParameter('state', '%published%')
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
        ;
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLatestPublished(int $limit): array // (BlogRSSController)
    {
        return $this->createQueryBuilder('p')
            ->where('p.hidden = true AND p.publishedAt < NOW()')
            ->andWhere('p.state LIKE :state')
            ->setParameter('state', '%published%')
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find articles by title & content (case insensitive).
     */
    public function searched(string $searchedTerm): array // (SearchedController)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.title) LIKE :searchedTerm OR LOWER(p.content) LIKE :searchedTerm')
            ->setParameter('searchedTerm', '%'.mb_strtolower($searchedTerm).'%')
            ->getQuery()
            ->getResult()
        ;
    }

    // rector

    /**
     * Find articles by title (case insensitive).
     *
     * @param string[] $titles
     *
     * @return Post[]
     */
    public function findByTitles(array $titles): array
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(p.title) IN (:title)')
            ->setParameter('title', array_map(fn (string $title) => mb_strtolower($title), $titles))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Finds an article by title (case insensitive).
     */
    public function findByTitle(string $title): ?Post
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(p.title) = :post')
            ->setMaxResults(1)
            ->setParameter('post', mb_strtolower($title))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Find all articles that start with the word.
     */
    public function searchByTitle(string $q): array
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(p.title) LIKE :q')
            ->orderBy('LENGTH(p.title)', 'ASC')
            ->setMaxResults(3)
            ->setParameter('q', mb_strtolower($q).'%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findForCategory(Category $category): array  // (BlogCategoryController)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.hidden = true')
            ->andWhere('c IN (:category)')
            ->setParameter('category', $category)
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function queryAllBlog(Category $category = null): Query // (BlogController)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.hidden = true')
            ->orderBy('p.publishedAt', 'DESC')
        ;

        if ($category) {
            $query = $query
                ->andWhere('p.category = :category')
                ->setParameter('category', $category)
            ;
        }

        return $query->getQuery();
    }

    /**
     * Get published posts.
     *
     * @return PaginationInterface (BlogController, BlogCategoryController)
     */
    public function findRecentPublished(
        int $page,
        Category $category = null,
        Tag $tag = null
    ): PaginationInterface {
        $data = $this->createQueryBuilder('p')
            ->andWhere('p.hidden = true')
            ->where('p.state LIKE :state')
            ->setParameter('state', '%published%')
            ->addOrderBy('p.publishedAt', 'DESC')
        ;

        if (isset($category)) {
            $data = $data
                ->join('p.categories', 'c')
                ->andWhere(':category IN (c)')
                ->setParameter('category', $category)
            ;
        }

        if (isset($tag)) {
            $data = $data
                ->join('p.tags', 't')
                ->andWhere(':tag IN (t)')
                ->setParameter('tag', $tag)
                ->setMaxResults(3)
            ;
        }

        $data
            ->getQuery()
            ->getResult()
        ;

        $posts = $this->paginatorInterface->paginate($data, $page, $this->pageSize);

        return $posts;
    }

    /**
     * Method findAllPost.
     *
     * @return Paginator (BlogController, BlogCategoryController)
     */
    public function findAllPost(int $page = 1, /* Category $category = null, */ Tag $tag = null): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect('p')
            // ->addSelect('c')
            ->addSelect('a', 't')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.tags', 't')
            // ->leftJoin('p.category', 'c')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('p.hidden = true')
            ->orderBy('p.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
        ;

        /*if (null !== $category) {
            $query
                ->andWhere(':category MEMBER OF p.category')
                ->setParameter('category', $category)
            ;
        }*/

        if (null !== $tag) {
            $query
                ->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag)
            ;
        }

        return (new Paginator($query))->paginate($page);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findAllTag(Tag $tag): array
    {
        return $this->createQueryBuilder('p')
            ->where(':tag MEMBER OF p.tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<array-key, Post>
     */
    public function getPostsByPage(int $page, int $maxResults = 10): array
    {
        /** @var array<array-key, Post> $posts */
        $posts = $this->createQueryBuilder('p')
            ->addSelect('c', 't')
            ->join('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.publishedAt', 'DESC')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('p.hidden = true')
            ->setMaxResults($maxResults)
            ->setFirstResult(($page - 1) * $maxResults)
            ->getQuery()
            ->getResult()
        ;

        return $posts;
    }

    public function findByQuery(string $query): array
    {
        if (empty($query)) {
            return [];
        }

        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns the blog posts after applying the specified search criterias.
     *
     * @param string                    $state
     * @param string                    $selecttags
     * @param bool                      $isHidden
     * @param string                    $keyword
     * @param string                    $slug
     * @param Category                  $category
     * @param int                       $limit
     * @param string                    $sort
     * @param string                    $order
     * @param string                    $otherthan
     *
     * @return QueryBuilder<Post> (BlogController)
     */
    public function getPosts($state, $selecttags, $isHidden, $keyword, $slug, $category, $limit, $sort, $order, $otherthan): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        if (!$selecttags) {
            $qb->select('p');

            if ('all' !== $state) {
                $qb->andWhere('p.state LIKE :state or :state LIKE p.state')->setParameter('state', '%published%');
            }

            if ('all' !== $isHidden) {
                $qb->andWhere('p.isHidden = :isHidden')->setParameter('isHidden', $isHidden);
            }

            if ('all' !== $keyword) {
                $qb->andWhere('p.title LIKE :keyword or :keyword LIKE p.title or :keyword LIKE p.content or p.content LIKE :keyword or :keyword LIKE p.tags or p.tags LIKE :keyword')->setParameter('keyword', '%'.$keyword.'%');
            }

            if ('all' !== $slug) {
                $qb->andWhere('p.slug = :slug')->setParameter('slug', $slug);
            }

            if ('all' !== $category) {
                $qb->leftJoin('p.category', 'category');
                $qb->andWhere('category.slug = :category')->setParameter('category', $category);
            }

            if ('all' !== $limit) {
                $qb->setMaxResults($limit);
            }

            if ('all' !== $otherthan) {
                $qb->andWhere('p.id != :otherthan')->setParameter('otherthan', $otherthan);
            }

            $qb->orderBy('p.'.$sort, $order);
        } else {
            $qb->select("SUBSTRING_INDEX(GROUP_CONCAT(p.tags SEPARATOR ','), ',', 8)");
        }

        return $qb;
    }
}
