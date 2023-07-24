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

use App\Entity\HomepageHeroSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomepageHeroSettings>
 *
 * @method HomepageHeroSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomepageHeroSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomepageHeroSettings[]    findAll()
 * @method HomepageHeroSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class HomepageHeroSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomepageHeroSettings::class);
    }

    public function save(HomepageHeroSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HomepageHeroSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
