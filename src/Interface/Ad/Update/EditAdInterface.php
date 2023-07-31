<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Ad\Update;

use App\Entity\Ad;

interface EditAdInterface
{
    public function __invoke(Ad $ad): void;
}
