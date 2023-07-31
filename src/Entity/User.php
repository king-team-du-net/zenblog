<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity;

use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasProfileTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[UniqueEntity(fields: ['email'], message: 'user.email_unique')]
#[UniqueEntity(fields: ['nickname'], message: 'user.nickname_unique')]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    use HasDeletedAtTrait;
    use HasProfileTrait;
    use HasTimestampTrait;

    final public const DEFAULT = 'ROLE_USER';
    final public const ADMINISTRATOR = 'ROLE_ADMINISTRATOR';
    final public const ADMIN = 'ROLE_ADMIN';
    final public const EDITOR = 'ROLE_EDITOR';

    public const USER_LIMIT = 10;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [self::DEFAULT];

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
    // #[Assert\NotBlank]
    private string $password = '';

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private Collection $posts;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'users')]
    private Collection $userRoles;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(mappedBy: 'booker', targetEntity: Booking::class)]
    private Collection $bookings;

    /**
     * @var Collection<int, Ad>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Ad::class)]
    private Collection $ads;

    /**
     * @var Collection<int, Ad>
     */
    #[ORM\ManyToMany(targetEntity: Ad::class, mappedBy: 'addedtofavoritesby', fetch: 'LAZY', cascade: ['remove'])]
    private Collection $favorites;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class, cascade: ['remove'])]
    private Collection $reviews;

    public function __construct()
    {
        $this->is_verified = false;
        $this->registrationTokenLifeTime = (new \DateTime('now'))->add(new \DateInterval('P1D'));

        $this->posts = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->ads = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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
        $roles[] = self::DEFAULT;

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

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setBooker($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getBooker() === $this) {
                $booking->setBooker(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ad>
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): static
    {
        if (!$this->ads->contains($ad)) {
            $this->ads->add($ad);
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): static
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ad>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Ad $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->addAddedtofavoritesby($this);
        }

        return $this;
    }

    public function removeFavorite(Ad $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removeAddedtofavoritesby($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }
}
