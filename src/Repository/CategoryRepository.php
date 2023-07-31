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
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Category[]
     */
    public function findCategoryCount(): array
    {
        $data = $this->createQueryBuilder('c')
            ->join('c.posts', 'p')
            ->where('p.hidden = true')
            ->groupBy('c.id')
            ->select('c', 'COUNT(c.id) as count')
            ->getQuery()
            ->getResult()
        ;

        return array_map(function (array $d) {
            $d[0]->setNumberOfPosts((int) $d['count']);

            return $d[0];
        }, $data);
    }

    /**
     * @return Category[] Returns an array of Category objects (BlogCategoryController)
     */
    public function findAllCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.hidden = true')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Category[] Returns an array of Category objects (HomePageController)
     */
    public function findLastRecent(int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return QueryBuilder<Category> (HomePageController)
     */
    public function findRecent(int $limit): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.hidden = true')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
        ;
    }

    /**
     * Returns the blog posts categories after applying the specified search criterias.
     *
     * @param bool   $isHidden
     * @param string $keyword
     * @param string $slug
     * @param int    $limit
     * @param string $order
     * @param string $sort
     */
    public function getPostCategories($isHidden, $keyword, $slug, $limit, $order, $sort): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c');

        if ('all' !== $isHidden) {
            $qb->andWhere('c.isHidden = :isHidden')->setParameter('isHidden', $isHidden);
        }

        if ('all' !== $keyword) {
            $qb->andWhere('c.name LIKE :keyword or :keyword LIKE c.name')->setParameter('keyword', '%'.$keyword.'%');
        }

        if ('all' !== $slug) {
            $qb->andWhere('c.slug = :slug')->setParameter('slug', $slug);
        }

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        if ('postscount' === $order) {
            $qb->leftJoin('c.posts', 'posts');
            $qb->addSelect('COUNT(posts.id) AS HIDDEN postscount');
            $qb->orderBy('postscount', 'DESC');
            $qb->groupBy('c.id');
        } else {
            $qb->orderBy($order, $sort);
        }

        return $qb;
    }
}
