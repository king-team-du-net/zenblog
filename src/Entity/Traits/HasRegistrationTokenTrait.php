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
use Symfony\Component\Uid\Uuid;

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
