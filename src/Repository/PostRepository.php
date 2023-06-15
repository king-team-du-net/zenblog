<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Post;
use Doctrine\ORM\Query;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
}
