<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\PageRepository;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasTitleAndSlugAndAssertTrait;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    use HasIdTrait;
    use HasTitleAndSlugAndAssertTrait;
    use HasContentTrait;
    use HasTimestampTrait;
}
