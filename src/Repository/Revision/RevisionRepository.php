<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Repository\Revision;

use App\Entity\Content;
use App\Entity\Revision\Revision;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Revision>
 *
 * @method Revision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Revision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Revision[]    findAll()
 * @method Revision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class RevisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Revision::class);
    }

    public function save(Revision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Revision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Revision[]
     */
    public function findPendingFor(User $user): array
    {
        return $this->queryAllForUser($user)
            ->andWhere('r.status = :status')
            ->setParameter('status', Revision::PENDING)
            ->getQuery()
            ->getResult()
        ;
    }

    public function queryAllForUser(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->addSelect('c')
            ->leftJoin('r.target', 'c')
            ->where('r.author = :user')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user)
        ;
    }

    public function findLatest(): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->setMaxResults(10)
            ->setParameter('status', Revision::PENDING)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findFor(User $user, Content $content): ?Revision
    {
        return $this->createQueryBuilder('r')
            ->where('r.author = :author')
            ->andWhere('r.target = :target')
            ->andWhere('r.status = :status')
            ->setParameters([
                'author' => $user,
                'target' => $content,
                'status' => Revision::PENDING,
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
