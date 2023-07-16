<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\UserRepository;
use App\Entity\Traits\HasProfileTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use function Symfony\Component\String\u;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\HasSocialLoggableTrait;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Traits\HasLastLoginAndBannedAtTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Traits\HasContactAndSocialMediaTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\HasReviewsAndFavoritesUsersTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[UniqueEntity(fields: ['email'], message: 'This email address is already in use.')]
#[UniqueEntity(fields: ['nickname'], message: 'This nickname is already used.')]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    use HasProfileTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    final public const DEFAULT = 'ROLE_USER';
    final public const ADMINISTRATOR = 'ROLE_ADMINISTRATOR';
    final public const ADMIN = 'ROLE_ADMIN';
    final public const EDITOR = 'ROLE_EDITOR';

    public const USER_LIMIT = 10;

    #[ORM\Column]
    private array $roles = [User::DEFAULT];

    #[Assert\NotBlank(groups: ['password'])]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        htmlPattern: '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$',
        groups: ['password']
    )]
    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING)]
    //#[Assert\NotBlank]
    private string $password = '';

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: "users")]
    private Collection $userRoles;

    public function __construct()
    {
        $this->is_verified = false;
        $this->registrationTokenLifeTime = (new \DateTime('now'))->add(new \DateInterval('P1D'));
        $this->posts = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->favorites = new ArrayCollection();
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

    /*
    public function getRoles()
    {
        $roles = $this->userRoles->map(function($role) {
            return $role->getName();
        })->toArray();
        // guarantee every user at least has ROLE_USER
        $roles[] = User::DEFAULT;

        return array_unique($roles);
    }
    */

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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
        $this->plainPassword = null;
    }

    /**
     * @return array{
     *      id: ?int,
     *      firstname: ?string,
     *      lastname: ?string,
     *      email: string,
     *      password: string,
     *      nickname: string,
     *      avatar: ?string,
     *      registrationToken: ?string,
     *      is_verified: ?bool
     * }
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'registrationToken' => null !== $this->registrationToken ? (string) $this->registrationToken : null,
            'is_verified' => $this->is_verified,
        ];
    }

    /**
     * @param array{
     *      id: ?int,
     *      firstname: ?string,
     *      lastname: ?string,
     *      email: string,
     *      password: string,
     *      nickname: string,
     *      avatar: ?string,
     *      registrationToken: ?string,
     *      is_verified: bool
     * } $data
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->nickname = $data['nickname'];
        $this->avatar = $data['avatar'];
        $this->registrationToken = null !== $data['registrationToken']
            ? Uuid::fromString($data['registrationToken'])
            : null;
        $this->is_verified = $data['is_verified'];
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

    /**
     * @return Collection<int, Role>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): static
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): static
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }
}
