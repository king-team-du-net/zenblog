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

use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasTimestampTrait;
use App\Entity\User\User;
use App\Repository\HomepageHeroSettingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: HomepageHeroSettingsRepository::class)]
#[Vich\Uploadable]
class HomepageHeroSettings
{
    use HasIdTrait;
    use HasTimestampTrait;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $paragraph = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'homepage_hero_custom_background', fileNameProperty: 'customBackgroundName', size: 'customBackgroundSize', mimeType: 'customBackgroundMimeType', originalName: 'customBackgroundOriginalName', dimensions: 'customBackgroundDimensions')]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'],
        mimeTypesMessage: 'The file should be an image'
    )]
    private ?File $customBackgroundFile = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $customBackgroundName = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $customBackgroundSize = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $customBackgroundMimeType = null;

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    private ?string $customBackgroundOriginalName = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $customBackgroundDimensions = [];

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $show_search_box = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(mappedBy: 'isonhomepageslider', targetEntity: Post::class, cascade: ['persist'])]
    private Collection $posts;

    /*
    #[ORM\OneToMany(mappedBy: 'isuseronhomepageslider', targetEntity: User::class)]
    private Collection $users;
    */

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        // $this->users = new ArrayCollection();
    }

    public function clearPosts(): void
    {
        $this->posts->clear();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getParagraph(): ?string
    {
        return $this->paragraph;
    }

    public function setParagraph(?string $paragraph): static
    {
        $this->paragraph = $paragraph;

        return $this;
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

    public function setCustomBackgroundFile(File|UploadedFile|null $customBackgroundFile)
    {
        $this->customBackgroundFile = $customBackgroundFile;

        if (null !== $customBackgroundFile) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    public function getCustomBackgroundFile(): ?File
    {
        return $this->customBackgroundFile;
    }

    public function getCustomBackgroundName(): ?string
    {
        return $this->customBackgroundName;
    }

    public function setCustomBackgroundName(?string $customBackgroundName): static
    {
        $this->customBackgroundName = $customBackgroundName;

        return $this;
    }

    public function getCustomBackgroundSize(): ?int
    {
        return $this->customBackgroundSize;
    }

    public function setCustomBackgroundSize(?int $customBackgroundSize): static
    {
        $this->customBackgroundSize = $customBackgroundSize;

        return $this;
    }

    public function getCustomBackgroundMimeType(): ?string
    {
        return $this->customBackgroundMimeType;
    }

    public function setCustomBackgroundMimeType(?string $customBackgroundMimeType): static
    {
        $this->customBackgroundMimeType = $customBackgroundMimeType;

        return $this;
    }

    public function getCustomBackgroundOriginalName(): ?string
    {
        return $this->customBackgroundOriginalName;
    }

    public function setCustomBackgroundOriginalName(?string $customBackgroundOriginalName): static
    {
        $this->customBackgroundOriginalName = $customBackgroundOriginalName;

        return $this;
    }

    public function getCustomBackgroundDimensions(): array
    {
        return $this->customBackgroundDimensions;
    }

    public function setCustomBackgroundDimensions(?array $customBackgroundDimensions): static
    {
        $this->customBackgroundDimensions = $customBackgroundDimensions;

        return $this;
    }

    public function getCustomBackgroundPath(): string
    {
        return 'uploads/homepage/hero/'.$this->customBackgroundName;
    }

    public function isShowSearchBox(): ?bool
    {
        return $this->show_search_box;
    }

    public function getShowSearchBox(): ?bool
    {
        return $this->show_search_box;
    }

    public function setShowSearchBox(?bool $show_search_box): static
    {
        $this->show_search_box = $show_search_box;

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
            $post->setIsonhomepageslider($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getIsonhomepageslider() === $this) {
                $post->setIsonhomepageslider(null);
            }
        }

        return $this;
    }
}
