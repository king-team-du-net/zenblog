<?php

namespace App\Repository;

use App\Entity\AdCategory;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<AdCategory>
 *
 * @method AdCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdCategory[]    findAll()
 * @method AdCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdCategory::class);
    }

    /**
     * Returns the ads categories after applying the specified search criterias.
     *
     * @param bool   $isHidden
     * @param string $keyword
     * @param string $slug
     * @param bool   $isFeatured
     * @param int    $limit
     * @param string $order
     * @param string $sort
     */
    public function getAdCategories($isHidden, $keyword, $slug, $isFeatured, $limit, $order, $sort): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select("DISTINCT c");

        if ('all' !== $isHidden) {
            $qb->andWhere('c.isHidden = :isHidden')->setParameter('isHidden', $isHidden);
        }

        if ('all' !== $keyword) {
            $qb->andWhere('c.name LIKE :keyword or :keyword LIKE c.name')->setParameter('keyword', '%'.$keyword.'%');
        }

        if ('all' !== $slug) {
            $qb->andWhere('c.slug = :slug')->setParameter('slug', $slug);
        }

        if ("all" !== $isFeatured) {
            $qb->andWhere("c.isFeatured = :isFeatured")->setParameter("isFeatured", $isFeatured);
            if ($isFeatured === true) {
                $qb->orderBy("c.featuredorder", "ASC");
            }
        }

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        if ('adscount' === $order) {
            $qb->leftJoin('c.ads', 'ads');
            $qb->addSelect('COUNT(ads.id) AS HIDDEN adscount');
            $qb->orderBy('adscount', 'DESC');
            $qb->groupBy('c.id');
        } else {
            $qb->orderBy($order, $sort);
        }

        return $qb;
    }
}
