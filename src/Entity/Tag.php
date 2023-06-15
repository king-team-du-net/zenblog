<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\HasTagAndCategoryTrait;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ORM\Table(name: 'blog_tag')]
class Tag
{
    use HasTagAndCategoryTrait;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: Post::class)]
    private Collection $posts;

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
            $post->setTag($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getTag() === $this) {
                $post->setTag(null);
            }
        }

        return $this;
    }
}
