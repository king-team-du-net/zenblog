<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Revision;

use App\Entity\Content;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\User;
use App\Repository\Revision\RevisionRepository;
use App\Utils\Revision\ContentNotSame;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RevisionRepository::class)]
/** @ContentNotSame() */
class Revision
{
    use HasIdTrait;

    use HasTimestampTrait;
    final public const PENDING = 0;
    final public const ACCEPTED = 1;
    final public const REJECTED = -1;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private string $content = '';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $author;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Content $target;

    #[ORM\Column(type: Types::INTEGER, length: 1, options: ['default' => '0'])]
    private int $status = self::PENDING;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTarget(): Content
    {
        return $this->target;
    }

    public function setTarget(Content $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
