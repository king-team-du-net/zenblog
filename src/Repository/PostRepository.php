<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Post;
use Doctrine\ORM\Query;
use App\Entity\Category;
use App\Helper\Paginator;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
    {
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

    /**
     * @param Category|null $category
     * @return Query
     */
    public function queryAllBlog(?Category $category = null): Query
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.isOnline = true')
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

    public function findAllPost(int $page = 1, /*Category $category = null,*/ Tag $tag = null): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect('p')
            //->addSelect('c')
            ->addSelect('a', 't')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.tags', 't')
            //->leftJoin('p.category', 'c')
            ->andWhere('p.publishedAt <= :now')
            ->andWhere('p.isOnline = true')
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
     * @return Post[]
     */
    public function findBySearchQuery(string $query, int $limit = Paginator::PAGE_SIZE): array
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
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, static function ($term) {
            return 2 <= $term->length();
        });
    }

    /**
     * @param  Post $post
     * @return Post|null
     */
    public function findPreviousPost(Post $post): ?Post
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

    /**
     * @param  Post $post
     * @return Post|null
     */
    public function findNextPost(Post $post): ?Post
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
     * @return Post[] Returns an array of Post objects
     */
    public function findAllCategory(Category $category): array
    {
        return $this->createQueryBuilder('p')
            ->where(':category MEMBER OF p.category')
            //->andWhere('p.isPortfolio = TRUE')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
        ;
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
     * @return QueryBuilder<Post>
     */
    public function findRecent(int $limit): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.isPublished = true AND p.createdAt < NOW()')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
        ;
    }

    /**
     * @return Post[] returns an array of Post objects similar with the given post
     */
    public function findSimilar(Post $post, int $maxResults = 4): array
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
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMostCommented(int $maxResults): array
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
}
