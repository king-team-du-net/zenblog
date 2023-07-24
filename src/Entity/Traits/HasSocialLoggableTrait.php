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

trait HasSocialLoggableTrait
{
    /*
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $discordId = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $githubId = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $googleId = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $facebookId = null;
    */

    #[ORM\Column(type: Types::STRING, length: 1000, nullable: true)]
    private ?string $facebookProfilePicture = null;

    #[ORM\Column(name: 'facebook_id', type: Types::STRING, length: 255, nullable: true)]
    protected ?string $facebook_id = null;

    #[ORM\Column(name: 'facebook_access_token', type: Types::STRING, length: 255, nullable: true)]
    protected ?string $facebook_access_token = null;

    #[ORM\Column(name: 'google_id', type: Types::STRING, length: 255, nullable: true)]
    protected ?string $google_id = null;

    #[ORM\Column(name: 'google_access_tokenn', type: Types::STRING, length: 255, nullable: true)]
    protected ?string $google_access_token = null;

    #[ORM\Column(type: Types::STRING, unique: true, nullable: true)]
    private ?string $apiKey = null;

    /*
    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(?string $discordId): static
    {
        $this->discordId = $discordId;

        return $this;
    }

    public function getGithubId(): ?string
    {
        return $this->githubId;
    }

    public function setGithubId(?string $githubId): static
    {
        $this->githubId = $githubId;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): static
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function useOauth(): bool
    {
        return null !== $this->googleId || null !== $this->facebookId || null !== $this->githubId;
    }
    */

    public function getFacebookProfilePicture(): ?string
    {
        return $this->facebookProfilePicture;
    }

    public function setFacebookProfilePicture(?string $facebookProfilePicture): static
    {
        $this->facebookProfilePicture = $facebookProfilePicture;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebook_id;
    }

    public function setFacebookId(?string $facebook_id): static
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    public function getFacebookAccessToken(): ?string
    {
        return $this->facebook_access_token;
    }

    public function setFacebookAccessToken(?string $facebook_access_token): static
    {
        $this->facebook_access_token = $facebook_access_token;

        return $this;
    }

    public function setGoogleId(?string $google_id): static
    {
        $this->google_id = $google_id;

        return $this;
    }

    public function getGoogleAccessToken(): ?string
    {
        return $this->google_access_token;
    }

    public function setGoogleAccessToken($google_access_token): static
    {
        $this->google_access_token = $google_access_token;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
