<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Ad\Create;

use App\Entity\Ad;

interface CreateAdInterface
{
    public function __invoke(Ad $ad): void;
}
