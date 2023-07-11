<?php

namespace App\Entity\Traits;

use App\Entity\User;
use App\Entity\Image\Media;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

trait HasLikesCollectionTrait
{
    /**
     * @var Collection<int, Media>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable('user_post_like')]
    private Collection $likes;

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
        return count($this->likes);
    }
}
