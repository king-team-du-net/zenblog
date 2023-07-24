<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    private ?Generator $faker = null;

    public function faker(): Generator
    {
        if (null === $this->faker) {
            $this->faker = Factory::create('fr_FR');
        }

        return $this->faker;
    }
}
