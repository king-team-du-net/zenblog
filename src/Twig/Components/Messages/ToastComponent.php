<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Twig\Components\Messages;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('toast', template: 'components/messages/toast.html.twig')]
final class ToastComponent
{
    public string $message;
    public string $type;

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getTitle(): string
    {
        return match ($this->type) {
            'success' => $this->translator->trans('message.success'),
            'error' => $this->translator->trans('message.error'),
            'warning' => $this->translator->trans('message.warning'),
            default => $this->translator->trans('message.default'),
        };
    }

    public function getColor(): string
    {
        return match ($this->type) {
            'success' => 'success',
            'error' => 'danger',
            'warning' => 'warning',
            default => 'info',
        };
    }

    public function getIcon(): string
    {
        return match ($this->type) {
            'success' => 'check-circle',
            'error' => 'slash-circle',
            'warning' => 'exclamation-circle',
            default => 'info-circle',
        };
    }
}
