<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Statisticator
{
    /**
     * Statisticator constructor.
     */
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function getStats(): array
    {
        $users = $this->getUsersCount();
        $posts = $this->getPostsCount();
        $categories = $this->getCategoriesCount();
        $tags = $this->getTagsCount();
        $comments = $this->getCommentsCount();
        $contacts = $this->getContactsCount();
        $pages = $this->getPagesCount();
        $settings = $this->getSettingsCount();

        return compact('users', 'posts', 'categories', 'tags', 'comments', 'contacts', 'pages', 'settings');
    }

    public function getPostsClassification(string $direction, int $maxResults = 6)
    {
        return $this->em->createQuery('
                SELECT AVG(c.rating) AS note, p.title, p.id, u.firstname, u.lastname, u.avatar
                FROM App\Entity\Comment AS c
                JOIN c.post AS p
                JOIN p.author AS u
                GROUP BY p
                ORDER BY note '.$direction
        )
            ->setMaxResults($maxResults)
            ->getResult()
        ;
    }

    public function getUsersCount()
    {
        return $this->em->createQuery('SELECT COUNT(u) FROM App\Entity\User AS u')->getSingleScalarResult();
    }

    public function getPostsCount()
    {
        return $this->em->createQuery('SELECT COUNT(p) FROM App\Entity\Post AS p')->getSingleScalarResult();
    }

    public function getCategoriesCount()
    {
        return $this->em->createQuery('SELECT COUNT(ca) FROM App\Entity\Category AS ca')->getSingleScalarResult();
    }

    public function getTagsCount()
    {
        return $this->em->createQuery('SELECT COUNT(t) FROM App\Entity\Tag AS t')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->em->createQuery('SELECT COUNT(c) FROM App\Entity\Comment AS c')->getSingleScalarResult();
    }

    public function getContactsCount()
    {
        return $this->em->createQuery('SELECT COUNT(co) FROM App\Entity\Contact AS co')->getSingleScalarResult();
    }

    public function getPagesCount()
    {
        return $this->em->createQuery('SELECT COUNT(pa) FROM App\Entity\Page AS pa')->getSingleScalarResult();
    }

    public function getSettingsCount()
    {
        return $this->em->createQuery('SELECT COUNT(s) FROM App\Entity\Setting AS s')->getSingleScalarResult();
    }
}
