<?php

declare(strict_types=1);

namespace App\Twig\Components\Design;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('header', template: 'components/design/header.html.twig')]
final class HeaderComponent
{
}
