<?php

namespace App\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait HasTagAndCategoryTrait
{
    use HasIdTrait;
    use HasNameAndSlugAndAssertTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
