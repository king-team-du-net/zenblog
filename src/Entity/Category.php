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

use App\Entity\Traits\HasBackgroundAndColorTrait;
use App\Entity\Traits\HasDeletedAtTrait;
use App\Entity\Traits\HasIsHiddenTrait;
use App\Entity\Traits\HasIconTrait;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasNameAndSlugAndAssertTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_category')]
class Category implements \Stringable
{
    use HasIdTrait;
    use HasNameAndSlugAndAssertTrait;
    use HasBackgroundAndColorTrait;
    use HasIconTrait;
    use HasIsHiddenTrait;
    use HasTimestampTrait;
    use HasDeletedAtTrait;

    public const CATEGORY_LIMIT = 10;

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
