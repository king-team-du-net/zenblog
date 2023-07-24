<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait HasRolesTrait
{
    use HasProfileTrait;

    #[ORM\Column]
    private array $roles = [User::DEFAULT];

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = User::DEFAULT;

        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): self
    {
        $this->roles = [];

        /** @var string $role */
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function getRole(): string
    {
        if ($this->hasRole(User::DEFAULT)) {
            return 'Default';
        } elseif ($this->hasRole(User::ADMIN)) {
            return 'Admin';
        } elseif ($this->hasRole(User::EDITOR)) {
            return 'Editor';
        } elseif ($this->hasRole('ROLE_SUPER_ADMIN') || $this->hasRole(User::ADMINISTRATOR)) {
            return 'Administrator';
        }

        return 'N/A';
    }

    public function getCrossRoleName(): string
    {
        if ($this->hasRole(User::DEFAULT)) {
            return $this->getFullName();
        } elseif ($this->hasRole(User::ADMIN)) {
            return $this->getFullName();
        } elseif ($this->hasRole(User::EDITOR)) {
            return $this->getFullName();
        } elseif ($this->hasRole(User::ADMINISTRATOR)) {
            return $this->getFullName();
        }

        return 'N/A';
    }

    public function addRole(string $role): self
    {
        $role = mb_strtoupper($role);
        if (User::DEFAULT === $role) {
            return $this;
        }

        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (false !== $key = array_search(mb_strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return \in_array(mb_strtoupper($role), $this->getRoles(), true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(User::ADMINISTRATOR);
    }

    public function setSuperAdmin($boolean): self
    {
        if (true === $boolean) {
            $this->addRole(User::ADMINISTRATOR);
        } else {
            $this->removeRole(User::ADMINISTRATOR);
        }

        return $this;
    }
}
