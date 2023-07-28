<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity;

use App\Entity\Image\Attachment;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasExcerptTrait;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasIsOnlineTrait;
use App\Entity\Traits\HasPublishedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasTitleAndSlugAndAssertTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['ad' => Ad::class, 'booking' => Booking::class, 'post' => Post::class])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
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

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'attachment_id', referencedColumnName: 'id')]
    private ?Attachment $attachment = null;

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

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachment $attachment): static
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getAttachmentName(): string
    {
        return str_replace(['.', ',', ':'], [' ', '', ''], $this->title ?: '');
    }

    public function isScheduled(): bool
    {
        return new \DateTimeImmutable() < $this->getCreatedAt();
    }

    public function isAd(): bool
    {
        return $this instanceof Ad;
    }

    public function isBooking(): bool
    {
        return $this instanceof Booking;
    }
}
