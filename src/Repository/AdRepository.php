<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\AdCategory;
use Doctrine\ORM\QueryBuilder;
use App\Entity\HomepageHeroSettings;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Ad>
 *
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    public function findBestAds($limit)
    {
        return $this->createQueryBuilder('a')
            ->select('a as annonce, AVG(c.rating) as avgRatings')
            ->join('a.comments', 'c')
            ->groupBy('a')
            ->orderBy('avgRatings', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns the ads after applying the specified search criterias.
     *
     * @param HomepageHeroSettings|null $isOnHomepageSlider
     * @param                           $addedtofavoritesby
     * @param bool                      $isPublished
     * @param string                    $keyword
     * @param string                    $slug
     * @param AdCategory                $adCategory
     * @param int                       $limit
     * @param string                    $sort
     * @param string                    $order
     * @param string                    $otherthan
     * @param int                       $count
     *
     * @return QueryBuilder<Ad> (AdController)
     */
    public function getAds($isOnHomepageSlider, $addedtofavoritesby, $isPublished, $keyword, $slug, $adCategory, $limit, $sort, $order, $otherthan, $count): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        if ($count) {
            $qb->select("COUNT(a)");
        } else {
            $qb->select("DISTINCT a");
        }

        if ('all' !== $keyword) {
            $qb->andWhere('a.title LIKE :keyword or :keyword LIKE a.title or :keyword LIKE a.content or a.content LIKE :keyword')->setParameter('keyword', '%'.$keyword.'%');
        }

        if ('all' !== $adCategory) {
            $qb->leftJoin('a.adCategory', 'adCategory');
            $qb->andWhere('adCategory.slug = :adCategory')->setParameter('adCategory', $adCategory);
        }

        if ('all' !== $slug) {
            $qb->andWhere('a.slug = :slug')->setParameter('slug', $slug);
        }

        if ('all' !== $addedtofavoritesby) {
            $qb->andWhere(':addedtofavoritesbyuser MEMBER OF a.addedtofavoritesby')->setParameter('addedtofavoritesbyuser', $addedtofavoritesby);
        }

        if (true === $isOnHomepageSlider) {
            $qb->andWhere('a.isonhomepageslider IS NOT NULL');
        }

        if ("all" !== $isPublished) {
            $qb->andWhere("a.isPublished = :isPublished")->setParameter("isPublished", $isPublished);
        }

        if ('all' !== $otherthan) {
            $qb->andWhere('a.id != :otherthan')->setParameter('otherthan', $otherthan);
            $qb->andWhere('a.id = :otherthan')->setParameter('otherthan', $otherthan);
        }

        $qb->orderBy($sort, $order);

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }
}
