<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public static function createIsApprovedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isApproved', true))
            ->orderBy(['createdAt' => 'ASC'])
        ;
    }

    /**
     * @return array<array-key, Comment>
     */
    public function getCommentsByEntityAndPage($value, int $page): array
    {
        if ($value instanceof Post) {
            $object = 'post';
        }

        return $this->createQueryBuilder('c')
            ->andWhere('c.' . $object . ' = :val')
            ->andWhere('c.isApproved = true')
            ->setParameter('val', $value->getId())
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(Comment::COMMENT_LIMIT)
            ->setFirstResult(($page - 1) * Comment::COMMENT_LIMIT)
            ->getQuery()
            ->getResult()
        ;
    }


    public function queryLatest(): Query
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->join('c.target', 't')
            ->leftJoin('c.author', 'a')
            ->addSelect('t', 'a')
            ->setMaxResults(7)
            ->getQuery()
        ;
    }

    public function findRecentComments($value) // (BlogController)
    {
        if ($value instanceof Post) {
            $object = 'post';
        }

        return $this->createQueryBuilder('c')
            ->andWhere('c.' . $object . ' = :val')
            ->andWhere('c.isApproved = true')
            // ->andWhere('c.state LIKE :state')
            ->setParameter('val', $value->getId())
            /*->setParameters([
                'val', $value->getId(),
                'state', '%published%'
            ])*/
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retrieves the latest comments created by the user.
     *
     * @param User $user
     * @param int $limit
     * @return Comment[] Returns an array of Comment objects
     */
    public function findLastByUser(User $user, int $limit): array //  (UserController)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author = :user')
            ->andWhere('c.isApproved = true')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns a comment avoiding the content binding.
     */
    public function findPartial(int $id): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->select('partial c.{id, nickname, email, content, createdAt}, partial u.{id, nickname, email}')
            ->where('c.id = :id')
            ->leftJoin('c.author', 'u')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getOneOrNullResult()
        ;
    }

    public function queryByIp(string $ip): QueryBuilder
    {
        return $this->createQueryBuilder('row')
            ->where('row.ip LIKE :ip')
            ->setParameter('ip', $ip)
        ;
    }

    /**
     * Find suspicious comments that are potentially spam.
     *
     * @param string[] $words
     */
    public function querySuspicious(array $words): QueryBuilder
    {
        $query = $this->createQueryBuilder('row')
            ->where('row.content LIKE :search')
            ->orderBy('row.createdAt', 'DESC')
            ->setParameter('search', '%http%')
        ;
        foreach ($words as $k => $word) {
            $query = $query->orWhere("row.content LIKE :spam{$k}")->setParameter("spam{$k}", "%{$word}%");
        }

        return $query;
    }

    /**
     * @return array<array-key, Comment>
     */
    public function getCommentsByPostAndPage(Post $post, int $page, int $maxResults = 5): array
    {
        /** @var array<array-key, Comment> $posts */
        $posts = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->where('c.post = :post')
            ->setParameter('post', $post)
            ->setMaxResults($maxResults)
            ->setFirstResult(($page - 1) * $maxResults)
            ->getQuery()
            ->getResult()
        ;

        return $posts;
    }

    public function findForPagination(?Post $post = null): Query // (CommentService)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
        ;

        if ($post) {
            $qb
                ->leftJoin('c.post', 'post')
                ->leftJoin('c.answers', 'answers')
                ->where($qb->expr()->eq('post.id', ':postId'))
                ->setParameter('postId', $post->getId())
            ;
        }

        return $qb->getQuery();
    }
}
