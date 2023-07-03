<?php

declare(strict_types=1);

namespace App\Twig\Components\Image;

use App\Entity\Image\Video;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('video', template: 'components/image/video.html.twig')]
final class VideoComponent
{
    public Video $video;
}
