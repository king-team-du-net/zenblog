<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasStateTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasExcerptTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasIsOnlineTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasPublishedAtTrait;
use App\Entity\Traits\HasTitleAndSlugAndAssertTrait;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['post' => Post::class])]
abstract class Content
{
    use HasIdTrait;
    use HasTitleAndSlugAndAssertTrait;
    use HasContentTrait;
    use HasExcerptTrait;
    use HasViewsTrait;
    use HasIsOnlineTrait;
    use HasPublishedAtTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    //#[ORM\ManyToOne(cascade: ['persist'])]
    //#[ORM\JoinColumn(name: 'attachment_id', referencedColumnName: 'id')]
    //private ?Attachment $image = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $author = null;

    public function __construct()
    {
        $this->views = 0;
    }

    public function __toString(): string
    {
        return sprintf('#%d %s', $this->getId(), $this->getTitle());
    }

    /*
    public function getImage(): ?Attachment
    {
        return $this->image;
    }

    public function setImage(?Attachment $image): static
    {
        $this->image = $image;

        return $this;
    }
    */

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /*
    public function getAttachmentName(): string
    {
        return str_replace(['.', ',', ':'], [' ', '', ''], $this->name ?: '');
    }
    */

    public function isScheduled(): bool
    {
        return new \DateTimeImmutable() < $this->getCreatedAt();
    }
}
