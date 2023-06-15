<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

trait HasProfileTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[Groups(['blogpost:read'])]
    private ?string $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $avatar;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 5, max: 180),
        Assert\NotNull,
        Assert\Email
    ]
    private ?string $email = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 2, max: 20)
    ]
    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $firstname = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 2, max: 20)
    ]
    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $lastname = null;

    #[
        Assert\NotBlank,
        Assert\NotNull,
        Assert\Length(min: 4, max: 30)
    ]
    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    #[Groups(['blogpost:read', 'comment:read'])]
    private string $nickname = '';

    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    #[Gedmo\Slug(fields: ['nickname'], unique: true, updatable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: true, options: ['default' => 'FR'])]
    private ?string $country = null;

    #[ORM\Column(type: Types::STRING, options: ['default' => null], nullable: true)]
    private ?string $theme = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    public function __toString(): string
    {
        return (string) $this->getFullName();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
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

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country ?: 'FR';
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): static
    {
        $this->theme = $theme;

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

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->avatar = 'https://avatars.dicebear.com/api/initials/' . $this->email . '.svg';
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->avatar = 'https://avatars.dicebear.com/api/initials/' . $this->email . '.svg';
    }
}
