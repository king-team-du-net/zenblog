<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * To get the aministrator.
     */
    public function getAdministrator()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_ADMINISTRATOR"%')
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    /**
     * Retrieve users according to their roles
     *
     * @param  string|null $roles
     * @return array
     */
    public function findUsers(?string $roles): array
    {
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.nickname', 'ASC')
        ;
        if ($roles) {
            $query
                ->andWhere('u.roles LIKE :val')
                ->setParameter('val', '%' . $roles . '%')
                ->setMaxResults(3)
            ;
        }
        return $query->getQuery()->getResult();
    }


    public function findAllUser(?string $roles)
    {
        if (!$roles) {
            $query = $this->createQueryBuilder('u')
                ->orderBy('u.nickname', 'ASC')
            ;
            return $query->getQuery()->getResult();
        } else {
            $query = $this->createQueryBuilder('u')
                ->where('u.roles LIKE :val')
                ->setParameter('val', $roles)
                ->orderBy('u.nickname', 'ASC')
            ;
            return $query->getQuery()->getResult();
        }
    }



    /**
     * @return User[]
     */
    public function clean(): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.deletedAt IS NOT NULL')
            ->andWhere('u.deletedAt < NOW()');

        /** @var User[] $users */
        $users = $query->getQuery()->getResult();
        $query->delete(User::class, 'u')->getQuery()->execute();

        return $users;
    }

    /**
     * Query used to retrieve a user for the login.
     */
    public function findForAuth(string $nickname): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('LOWER(u.email) = :nickname')
            ->orWhere('LOWER(u.nickname) = :nickname')
            ->setMaxResults(1)
            ->setParameter('nickname', mb_strtolower($nickname))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * List of banned users
     */
    public function queryBanned(): QueryBuilder
    {
        return $this->createQueryBuilder('u')->where('u.bannedAt IS NOT NULL');
    }
}
