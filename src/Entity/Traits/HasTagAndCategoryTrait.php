<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait HasTagAndCategoryTrait
{
    use HasDeletedAtTrait;
    use HasIdTrait;
    use HasNameAndSlugAndAssertTrait;
    use HasTimestampTrait;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
