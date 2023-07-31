<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity;

use App\Entity\Image\Media;
use App\Entity\Traits\HasContactAndSocialMediaTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasIsFeaturedTrait;
use App\Entity\Traits\HasIsPortfolioTrait;
use App\Entity\Traits\HasIsPublishedTrait;
use App\Entity\Traits\HasPublishedAtTrait;
use App\Entity\Traits\HasReferenceTrait;
use App\Entity\Traits\HasStateTrait;
use App\Entity\Traits\HasTagTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasTitleAndSlugAndAssertTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdRepository::class)]
#[UniqueEntity(fields: ['title'], message: 'ad.title_unique')]
// #[ORM\Table(name: 'ad')]
class Ad
{
    use HasIdTrait;
    use HasTitleAndSlugAndAssertTrait;
    use HasIsFeaturedTrait;
    use HasIsPortfolioTrait;
    use HasIsPublishedTrait;
    use HasReferenceTrait;
    use HasViewsTrait;
    use HasTagTrait;
    use HasContactAndSocialMediaTrait;
    //use HasStateTrait;
    //use HasPublishedAtTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    public const AD_LIMIT = 4;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private ?string $excerpt = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $price = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $rooms = null;

    #[ORM\ManyToOne(inversedBy: 'ads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'ads', cascade: ['persist'])]
    private ?HomepageHeroSettings $isonhomepageslider = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Booking::class)]
    private Collection $bookings;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Comment::class, orphanRemoval: true, cascade: ['persist'])]
    #[ORM\OrderBy(['publishedAt' => 'DESC'])]
    private Collection $comments;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Review::class, cascade: ['remove'])]
    private Collection $reviews;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'favorites', cascade: ['persist', 'merge'])]
    #[ORM\JoinTable(name: 'favorites')]
    #[ORM\JoinColumn(name: 'ad_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $addedtofavoritesby;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\NotNull(groups: ['create', 'update'])]
    private bool $enablereviews = true;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['ad:read'])]
    private string $cover = '';

    #[Assert\Image(groups: ['cover'], maxSize: '1M', maxRatio: 4 / 3, minRatio: 4 / 3)]
    #[Assert\NotNull(groups: ['cover'])]
    private ?UploadedFile $coverFile = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Media::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    private Collection $medias;

    #[ORM\ManyToOne(inversedBy: 'ads')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(groups: ['create', 'update'])]
    private ?AdCategory $adCategory = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->addedtofavoritesby = new ArrayCollection();
        $this->reference = $this->generateReference(10);
        $this->views = 0;
    }

    public function __toString(): string
    {
        return sprintf('#%d %s', $this->getId(), $this->getTitle());
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): static
    {
        $this->rooms = $rooms;

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

    public function getIsonhomepageslider(): ?HomepageHeroSettings
    {
        return $this->isonhomepageslider;
    }

    public function setIsonhomepageslider(?HomepageHeroSettings $isonhomepageslider): static
    {
        $this->isonhomepageslider = $isonhomepageslider;

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
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }

    /**
     * Gets a table of days that are not available for this ad.
     *
     * @return array An array of DateTime objects representing occupancy days
     */
    public function getNotAvailableDays()
    {
        $notAvailableDays = [];

        /** @var Booking $booking */
        foreach ($this->bookings as $booking) {
            // Calculate the days between the date of arrival and departure
            $resultat = range(
                $booking->getStartDate()->getTimestamp(),
                $booking->getEndDate()->getTimestamp(),
                24 * 60 * 60
            );

            $days = array_map(function ($dayTimestamp) {
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);

            $notAvailableDays = array_merge($notAvailableDays, $days);
        }

        return $notAvailableDays;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }

    /**
     * Provides the overall average rating for this ad.
     */
    public function getAvgRatings(): float
    {
        // Calculate sum of ratings
        $sum = array_reduce($this->comments->toArray(), function ($total, $comment) {
            return $total + $comment->getRating();
        }, 0);

        // Divide to get the averages
        if (\count($this->comments) > 0) {
            return $sum / \count($this->comments);
        }

        return 0;
    }

    /**
     * Returns a user's comment on an article.
     */
    public function getCommentFromAuthor(User $author): ?Comment
    {
        foreach ($this->comments as $comment) {
            if ($comment->getAuthor() === $author) {
                return $comment;
            }
        }

        return null;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setAd($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getAd() === $this) {
                $review->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAddedtofavoritesby(): Collection
    {
        return $this->addedtofavoritesby;
    }

    public function addAddedtofavoritesby(User $addedtofavoritesby): static
    {
        if (!$this->addedtofavoritesby->contains($addedtofavoritesby)) {
            $this->addedtofavoritesby->add($addedtofavoritesby);
        }

        return $this;
    }

    public function removeAddedtofavoritesby(User $addedtofavoritesby): static
    {
        $this->addedtofavoritesby->removeElement($addedtofavoritesby);

        return $this;
    }

    public function isAddedToFavoritesBy(User $user): bool
    {
        return $this->addedtofavoritesby->contains($user);
    }

    public function isEnablereviews(): bool
    {
        return $this->enablereviews;
    }

    public function getEnablereviews(): bool
    {
        return $this->enablereviews;
    }

    public function setEnablereviews(bool $enablereviews): static
    {
        $this->enablereviews = $enablereviews;

        return $this;
    }

    public function isRatedBy(User $user): Review
    {
        foreach ($this->reviews as $review) {
            if ($review->getUser() === $user) {
                return $review;
            }
        }

        return false;
    }

    public function getRatingsPercentageForRating($rating): int|float
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }

        return round(($this->getRatingsCountForRating($rating) / $this->countVisibleReviews()) * 100, 1);
    }

    public function getRatingsCountForRating($rating): int
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }

        $ratingCount = 0;
        /** @var Review $review */
        foreach ($this->reviews as $review) {
            if ($review->getVisible() && $review->getRating() === $rating) {
                ++$ratingCount;
            }
        }

        return $ratingCount;
    }

    public function getRatingAvg(): int|float
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }
        $ratingAvg = 0;

        /** @var Review $review */
        foreach ($this->reviews as $review) {
            if ($review->getVisible()) {
                $ratingAvg += $review->getRating();
            }
        }

        return round($ratingAvg / $this->countVisibleReviews(), 1);
    }

    public function getRatingPercentage(): int|float
    {
        if (!$this->countVisibleReviews()) {
            return 0;
        }

        $ratingPercentage = 0;

        /** @var Review $review */
        foreach ($this->reviews as $review) {
            if ($review->getVisible()) {
                $ratingPercentage += $review->getRatingPercentage();
            }
        }

        return round($ratingPercentage / $this->countVisibleReviews(), 1);
    }

    public function countVisibleReviews(): int
    {
        $count = 0;

        /** @var Review $review */
        foreach ($this->reviews as $review) {
            if ($review->getVisible()) {
                ++$count;
            }
        }

        return $count;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCoverFile(): ?UploadedFile
    {
        return $this->coverFile;
    }

    public function setCoverFile(?UploadedFile $coverFile): static
    {
        $this->coverFile = $coverFile;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): static
    {
        $media->setAd($this);
        $this->medias->add($media);

        return $this;
    }

    public function removeMedia(Media $media): static
    {
        $media->setAd(null);
        $this->medias->removeElement($media);

        return $this;
    }

    public function getAdCategory(): ?AdCategory
    {
        return $this->adCategory;
    }

    public function setAdCategory(?AdCategory $adCategory): static
    {
        $this->adCategory = $adCategory;

        return $this;
    }
}
