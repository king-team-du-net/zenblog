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

use App\Entity\AppLayoutSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
