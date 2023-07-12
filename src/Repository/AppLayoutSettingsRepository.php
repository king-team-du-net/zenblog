<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AppLayoutSettings;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<AppLayoutSettings>
 *
 * @method AppLayoutSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppLayoutSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppLayoutSettings[]    findAll()
 * @method AppLayoutSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class AppLayoutSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppLayoutSettings::class);
    }
}
