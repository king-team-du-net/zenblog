<?php

declare(strict_types=1);

namespace App\Entity\Image;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity]
class Video extends Media
{
    #[ORM\Column]
    #[Assert\NotBlank]
    private string $url;

    public function type(): string
    {
        return 'video';
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getProvider(): Provider
    {
        return Provider::fromUrl($this->url);
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (!Provider::isValid($this->url)) {
            $context->buildViolation('The video url does not match any provider.')
                ->atPath('url')
                ->addViolation()
            ;
        }
    }
}
