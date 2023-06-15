<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @implements DataTransformerInterface<User, string>
 */
final class EmailUserTransformer implements DataTransformerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function transform(mixed $value): string
    {
        return '';
    }

    public function reverseTransform($value): User
    {
        $user = $this->userRepository->findOneBy(['email' => $value]);

        if (!$user instanceof User) {
            throw new TransformationFailedException($this->translator->trans('This email address does not exist.'));
        }

        return $user;
    }
}
