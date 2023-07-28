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

use App\Entity\Image\Media;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasExcerptTrait;
use App\Entity\Traits\HasHiddenTrait;
use App\Entity\Traits\HasPublishedAtTrait;
use App\Entity\Traits\HasStateTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Meilisearch\Bundle\Searchable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_post')]
class Post
{
    use HasContentTrait;
    use HasDeletedAtTrait;
    use HasExcerptTrait;
    use HasHiddenTrait;
    use HasPublishedAtTrait;
    use HasStateTrait;
    use HasTimestampTrait;
    use HasViewsTrait;

    public const POST_LIMIT = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(Searchable::NORMALIZATION_GROUP)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 128)]
    #[Groups(['post:read', Searchable::NORMALIZATION_GROUP])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'], unique: true, updatable: true)]
    #[Groups(['post:read', Searchable::NORMALIZATION_GROUP])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read'])]
    private ?Category $category = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'blog_post_tag')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    #[Assert\Count(max: 4, maxMessage: 'post.too_many_tags')]
    private Collection $tags;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true, cascade: ['persist'])]
    #[ORM\OrderBy(['publishedAt' => 'DESC'])]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read'])]
    private ?User $author = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $readtime = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable('user_post_like')]
    private Collection $likes;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['post:read'])]
    private string $cover = '';

    #[Assert\Image(groups: ['cover'], maxSize: '1M', maxRatio: 4 / 3, minRatio: 4 / 3)]
    #[Assert\NotNull(groups: ['cover'])]
    private ?UploadedFile $coverFile = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Media::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    private Collection $medias;

    public function __construct()
    {
        $this->views = 0;
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('#%d %s', $this->getId(), $this->getTitle());
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getIsApprovedComments(): Collection
    {
        return $this->getComments()->matching(CommentRepository::createIsApprovedCriteria());
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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Provides the overall average rating for this article.
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getReadtime(): ?int
    {
        return $this->readtime;
    }

    public function setReadtime(?int $readtime): static
    {
        $this->readtime = $readtime;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(User $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        }

        return $this;
    }

    public function removeLike(User $like): static
    {
        $this->likes->removeElement($like);

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        return $this->likes->contains($user);
    }

    /**
     * Get the number of likes.
     */
    public function howManyLikes(): int
    {
        return \count($this->likes);
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
        $media->setPost($this);
        $this->medias->add($media);

        return $this;
    }

    public function removeMedia(Media $media): static
    {
        $media->setPost(null);
        $this->medias->removeElement($media);

        return $this;
    }
}
