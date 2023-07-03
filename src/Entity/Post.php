<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Image\Media;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\PostRepository;
use App\Entity\Traits\HasStateTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Entity\Traits\HasHiddenTrait;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasExcerptTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasIsOnlineTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasPublishedAtTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Traits\HasTitleAndSlugAndAssertTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_post')]
class Post
{
    use HasIdTrait;
    //use HasTitleAndSlugAndAssertTrait;
    use HasContentTrait;
    use HasExcerptTrait;
    use HasViewsTrait;
    use HasStateTrait;
    use HasHiddenTrait;
    use HasPublishedAtTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    public const NUM_ITEMS_PER_PAGE = 10;

    #[ORM\Column(type: Types::STRING, length: 128)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 1, max: 128)
    ]
    #[Groups(['post:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'], unique: true, updatable: true)]
    #[Groups(['post:read'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read'])]
    private ?Category $category = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'blog_post_tag')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    #[Assert\Count(max: 4, maxMessage: 'post.too_many_tags')]
    private Collection $tags;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true, cascade: ['persist'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read'])]
    private ?User $author = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $readtime = null;

    #[ORM\Column(type: Types::STRING)]
    private string $image = '';

    #[Assert\Image(maxSize: '1M', maxRatio: 4/3, minRatio: 4/3)]
    #[Assert\NotNull(groups: ['create'])]
    private ?UploadedFile $imageFile = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['post:read'])]
    private string $cover = '';

    #[Assert\Image(groups: ['cover'], maxSize: '1M', maxRatio: 4/3, minRatio: 4/3)]
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
    }

    public function __toString(): string
    {
        return sprintf('#%d %s', $this->getId(), $this->getTitle());
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
     * Allows to obtain the average of the votes of an article
     * @return float|int
     */
    public function getAvgRatings()
    {
        //Get the sum of the notes
        $sum = array_reduce($this->comments->toArray(), function ($total, $comment) {
            return $total + $comment->getRating();
        }, 0);

        //Calculate the average rating of the articles
        if (count($this->comments) > 0) {
            return $sum / count($this->comments);
        }

        return 0;
    }

    /**
     * Returns a user's comment on an article
     * @param User $author
     * @return mixed|null
     */
    public function getCommentFromAuthor(User $author)
    {
        foreach ($this->comments as $comment) {
            if ($comment->getAuthor() ===  $author) return $comment;
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

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): void
    {
        $this->imageFile = $imageFile;
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
