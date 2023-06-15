<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\PostRepository;
use App\Entity\Traits\HasStateTrait;
use App\Entity\Traits\HasViewsTrait;
use App\Entity\Traits\HasContentTrait;
use App\Entity\Traits\HasExcerptTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasIsOnlineTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\Traits\HasPublishedAtTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Traits\HasTitleAndSlugAndAssertTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_post')]
class Post
{
    use HasIdTrait;
    use HasTitleAndSlugAndAssertTrait;
    use HasContentTrait;
    use HasExcerptTrait;
    use HasViewsTrait;
    use HasStateTrait;
    use HasIsOnlineTrait;
    use HasPublishedAtTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    public const NUM_ITEMS_PER_PAGE = 10;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $tag = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $readtime = null;

    public function __construct()
    {
        $this->views = 0;
        $this->comments = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('#%d %s', $this->getId(), $this->getTitle());
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

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
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

    public function setReadtime(?int $readtime): self
    {
        $this->readtime = $readtime;

        return $this;
    }
}
