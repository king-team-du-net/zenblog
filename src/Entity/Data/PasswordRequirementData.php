<?php

namespace App\Entity\Data;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordRequirementData
{
    #[Assert\NotBlank]
    private ?int $length;

    private bool $uppercaseLetters;

    private bool $digits;

    private bool $specialCharacters;

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getUppercaseLetters(): bool
    {
        return $this->uppercaseLetters;
    }

    public function setUppercaseLetters(bool $uppercaseLetters): self
    {
        $this->uppercaseLetters = $uppercaseLetters;

        return $this;
    }

    public function getDigits(): bool
    {
        return $this->digits;
    }

    public function setDigits(bool $digits): self
    {
        $this->digits = $digits;

        return $this;
    }

    public function getSpecialCharacters(): bool
    {
        return $this->specialCharacters;
    }

    public function setSpecialCharacters(bool $specialCharacters): self
    {
        $this->specialCharacters = $specialCharacters;

        return $this;
    }
}
