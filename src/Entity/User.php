<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\UserRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    use HasIdTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    final public const DEFAULT = 'ROLE_USER';
    final public const ADMINISTRATOR = 'ROLE_ADMINISTRATOR';
    final public const ADMIN = 'ROLE_ADMIN';
    final public const EDITOR = 'ROLE_EDITOR';

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['comment:read'])]
    private ?string $avatar = null;

    #[Assert\NotNull(groups: ['avatar'])]
    #[ImageConstraint(groups: ['avatar'])]
    private ?UploadedFile $avatarFile = null;

    #[
        Assert\NotBlank,
        Assert\NotNull,
        Assert\Length(min: 4, max: 30)
    ]
    #[ORM\Column(type: Types::STRING, length: 30, unique: true)]
    private string $nickname = '';

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
        Assert\Length(min: 5, max: 180),
        Assert\NotNull,
        Assert\Email
    ]
    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $designation = null;

    #[ORM\Column]
    private array $roles = [User::DEFAULT];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $password = '';

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getFullName();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = User::DEFAULT;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }
}
