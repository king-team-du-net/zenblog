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

#[AsTwigComponent('dropdown', template: 'components/design/dropdown.html.twig')]
final class DropdownComponent
{
    public string $tag = 'div';

    public string $class = '';

    public string $toggle = 'button';

    public string $toggleClass = 'btn btn-primary';

    public string $direction = 'end';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];
}
