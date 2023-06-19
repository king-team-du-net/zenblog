<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\CategoryRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasTimestampTrait;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\HasTagAndCategoryTrait;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Traits\HasNameAndSlugAndAssertTrait;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_category')]
class Category implements \Stringable
{
    use HasIdTrait;
    use HasNameAndSlugAndAssertTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $numberOfPosts = 0;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class)]
    private Collection $posts;

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getNumberOfPosts(): int
    {
        return $this->numberOfPosts;
    }

    public function setNumberOfPosts(int $numberOfPosts): static
    {
        $this->numberOfPosts = $numberOfPosts;

        return $this;
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
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
