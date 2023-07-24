<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Twig\Components\Design;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('modal', template: 'components/design/modal.html.twig')]
final class ModalComponent
{
    public string $id;

    public string $title;

    public bool $closeButton = true;
}
