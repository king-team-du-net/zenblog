<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\TagRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasBackgroundAndColorTrait;
use App\Entity\Traits\HasNameAndSlugAndAssertTrait;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_tag')]
class Tag implements \Stringable
{
    use HasIdTrait;
    use HasBackgroundAndColorTrait;
    use HasNameAndSlugAndAssertTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
