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

trait HasIsVerifiedAndSuspendedTrait
{
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $suspended = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $is_verified = false;

    public function isSuspended(): bool
    {
        return $this->suspended;
    }

    public function setSuspended(bool $suspended): static
    {
        $this->suspended = $suspended;

        return $this;
    }

    public function isIsVerified(): bool
    {
        return $this->is_verified;
    }

    public function getIsVerified(): bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }
}
