<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Ad\Delete;

use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteAd implements DeleteAdInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function __invoke(Ad $ad): void
    {
        $this->em->remove($ad);
        $this->em->flush();
    }
}
