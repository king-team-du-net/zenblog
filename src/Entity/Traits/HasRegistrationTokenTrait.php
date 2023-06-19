<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

trait HasRegistrationTokenTrait
{
    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $registrationToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationTokenLifeTime = null;

    public function getRegistrationToken(): ?Uuid
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?Uuid $registrationToken): void
    {
        $this->registrationToken = $registrationToken;
    }

    public function hasValidatedRegistration(): bool
    {
        return null === $this->registrationToken;
    }

    public function getRegistrationTokenLifeTime(): ?\DateTimeInterface
    {
        return $this->registrationTokenLifeTime;
    }

    public function setRegistrationTokenLifeTime(\DateTimeInterface $registrationTokenLifeTime): static
    {
        $this->registrationTokenLifeTime = $registrationTokenLifeTime;

        return $this;
    }
}
