<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\SettingRepository;
use App\Entity\Traits\HasTimestampTrait;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Setting
{
    use HasIdTrait;
    use HasTimestampTrait;

    //#[ORM\Column(type: Types::STRING, length: 255)]
    //private string $label = '';

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $name = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private $value;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $type = null;

    /*
    public function __construct(string $label, string $name, string $value, ?string $type = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }
    */

    public function __toString(): string
    {
        return $this->value ?? '';
    }

    /*
    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }
    */

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
