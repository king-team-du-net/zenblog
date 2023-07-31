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

interface DeleteAdInterface
{
    public function __invoke(Ad $ad): void;
}
