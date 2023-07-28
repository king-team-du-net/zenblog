<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use function Symfony\Component\String\u;

trait HasProfileTrait
{
    use HasContactAndSocialMediaTrait;
    use HasLastLoginAndBannedAtTrait;
    use HasSocialLoggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['post:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['comment:read'])]
    private ?string $avatar = null;

    #[Assert\NotNull(groups: ['avatar'])]
    #[ImageConstraint(groups: ['avatar'])]
    private ?UploadedFile $avatarFile = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 30)]
    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    #[Groups(['post:read', 'comment:read'])]
    private string $nickname = '';

    #[ORM\Column(length: 30, unique: true)]
    #[Gedmo\Slug(fields: ['nickname'], unique: true, updatable: true)]
    private ?string $slug = null;

    #[Assert\Length(min: 2, max: 20)]
    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $firstname = null;

    #[Assert\Length(min: 2, max: 20)]
    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $lastname = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 180)]
    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $designation = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    #[Assert\NotNull]
    private bool $team = false;

    public function __toString(): string
    {
        return (string) $this->getFullName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatarFile(): ?UploadedFile
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?UploadedFile $avatarFile): User
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }

    public function getFullName(): string
    {
        return u(sprintf('%s %s', $this->firstname, $this->lastname))->upper()->toString();
    }

    public function getLastname(): ?string
    {
        return u($this->lastname)->upper()->toString();
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return u($this->firstname)->upper()->toString();
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = trim($nickname ?: '');

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return u($this->designation)->upper()->toString();
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function isTeam(): bool
    {
        return $this->team;
    }

    public function getTeam(): bool
    {
        return $this->team;
    }

    public function setTeam(bool $team): static
    {
        $this->team = $team;

        return $this;
    }
}
