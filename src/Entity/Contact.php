<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasIPTrait;
use App\Repository\ContactRepository;
use App\Entity\Traits\HasTimestampTrait;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

/** Saves contact requests to limit spam. */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use HasIdTrait;
    use HasIPTrait;
    use HasTimestampTrait;

    #[
        Assert\NotBlank,
        Assert\Length(min: 4, max: 100)
    ]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $fullname = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 5, max: 180),
        Assert\NotNull,
        Assert\Email
    ]
    #[ORM\Column(type: Types::STRING, length: 180)]
    private ?string $email = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 10, max: 255)
    ]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $subject = null;

    #[
        Assert\NotBlank,
        Assert\Length(min: 10)
    ]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isSend = false;

    public function getFullname(): ?string
    {
        return u($this->fullname)->upper()->toString();
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isIsSend(): bool
    {
        return $this->isSend;
    }

    public function getIsSend(): bool
    {
        return $this->isSend;
    }

    public function setIsSend(bool $isSend): static
    {
        $this->isSend = $isSend;

        return $this;
    }
}
