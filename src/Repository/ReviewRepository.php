<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Review;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function save(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns the reviews after applying the specified search criterias
     *
     * @param string    $keyword
     * @param string    $slug
     * @param User|null $user
     * @param Ad|null   $ad
     * @param bool|null $visible
     * @param int|null  $rating
     * @param int       $minrating
     * @param int       $maxrating
     * @param int       $limit
     * @param int       $count
     * @param string    $sort
     * @param string    $order
     */
    public function getReviews($keyword, $slug, $user, $ad, $visible, $rating, $minrating, $maxrating, $limit, $count, $sort, $order): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r');

        if ($count) {
            $qb->select('COUNT(DISTINCT r)');
        } else {
            $qb->select('DISTINCT r');
        }

        if ('all' !== $keyword) {
            $qb->andWhere('r.headline LIKE :keyword or :keyword LIKE r.headline or r.details LIKE :keyword or :keyword LIKE r.details')->setParameter('keyword', '%'.$keyword.'%');
        }

        if ('all' !== $slug) {
            $qb->andWhere('r.slug = :slug')->setParameter('slug', $slug);
        }

        if ('all' !== $user) {
            $qb->leftJoin('r.user', 'user');
            $qb->andWhere('user.slug = :user')->setParameter('user', $user);
        }

        if ('all' !== $ad || 'all' !== $user) {
            $qb->leftJoin('r.ad', 'ad');
        }

        if ('all' !== $visible) {
            $qb->andWhere('r.visible = :visible')->setParameter('visible', $visible);
        }

        if ('all' !== $rating) {
            $qb->andWhere('r.rating = :rating')->setParameter('rating', $rating);
        }

        if ('all' !== $minrating) {
            $qb->andWhere('r.rating >= :minrating')->setParameter('minrating', $minrating);
        }

        if ('all' !== $maxrating) {
            $qb->andWhere('r.rating <= :maxrating')->setParameter('maxrating', $maxrating);
        }

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        if ($sort) {
            $qb->orderBy('r.'.$sort, $order);
        }

        return $qb;
    }
}
