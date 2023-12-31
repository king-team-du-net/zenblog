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

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('alert', template: 'components/messages/alert.html.twig')]
final class AlertComponent
{
    public string $type;

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
