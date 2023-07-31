<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait HasContactAndSocialMediaTrait
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    private ?string $youtubeurl = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    private ?string $externallink = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    private ?string $phonenumber = null;

    #[ORM\Column(type: Types::STRING, length: 180, nullable: true)]
    #[Assert\Length(max: 180)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    private ?string $twitterurl = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    private ?string $instagramurl = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    #[Groups(['post:read'])]
    private ?string $facebookurl = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    private ?string $googleplusurl = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['default' => '#'])]
    #[Assert\Length(max: 255)]
    private ?string $linkedinurl = null;

    #[ORM\Column(type: Types::STRING, length: 500, nullable: true)]
    #[Assert\Length(max: 500)]
    private ?string $artists = null;

    public function hasContactAndSocialMedia(): bool
    {
        return $this->externallink || $this->phonenumber || $this->youtubeurl || $this->twitterurl || $this->instagramurl || $this->email || $this->facebookurl || $this->googleplusurl || $this->linkedinurl;
    }

    public function getYoutubeurl(): ?string
    {
        return $this->youtubeurl;
    }

    public function setYoutubeurl(?string $youtubeurl): static
    {
        $this->youtubeurl = $youtubeurl;

        return $this;
    }

    public function getExternallink(): ?string
    {
        return $this->externallink;
    }

    public function setExternallink(?string $externallink): static
    {
        $this->externallink = $externallink;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTwitterUrl(): ?string
    {
        return $this->twitterurl;
    }

    public function setTwitterUrl(?string $twitterurl): static
    {
        $this->twitterurl = $twitterurl;

        return $this;
    }

    public function getInstagramUrl(): ?string
    {
        return $this->instagramurl;
    }

    public function setInstagramUrl(?string $instagramurl): static
    {
        $this->instagramurl = $instagramurl;

        return $this;
    }

    public function getFacebookUrl(): ?string
    {
        return $this->facebookurl;
    }

    public function setFacebookUrl(?string $facebookurl): static
    {
        $this->facebookurl = $facebookurl;

        return $this;
    }

    public function getGoogleplusUrl(): ?string
    {
        return $this->googleplusurl;
    }

    public function setGoogleplusUrl(?string $googleplusurl): static
    {
        $this->googleplusurl = $googleplusurl;

        return $this;
    }

    public function getLinkedinUrl(): ?string
    {
        return $this->linkedinurl;
    }

    public function setLinkedinUrl(?string $linkedinurl): static
    {
        $this->linkedinurl = $linkedinurl;

        return $this;
    }

    public function getArtists(): ?string
    {
        return $this->artists;
    }

    public function setArtists(?string $artists): static
    {
        $this->artists = $artists;

        return $this;
    }
}
