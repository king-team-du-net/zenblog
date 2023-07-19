<?php

declare(strict_types=1);

namespace App\Interface\Uploads\Picture;

use League\Glide\Urls\UrlBuilderFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ResizerPicture
{
    public function __construct(
        private readonly string $signKey,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * Returns a resized URL for a given image.
     * 
     * @param null|string $url
     * @param null|int $width
     * @param null|int $height
     * @return string
     */
    public function resize(?string $url, ?int $width = null, ?int $height = null): string
    {
        if (null === $url || empty($url)) {
            return '';
        }

        if (null === $width && null === $height) {
            $url = $this->urlGenerator->generate('image_jpg', ['path' => trim($url, '/')]);
        } else {
            $url = $this->urlGenerator->generate('image_resizer', ['path' => trim($url, '/'), 'width' => $width, 'height' => $height]);
        }

        /** Key to sign URLs for resizing https://glide.thephpleague.com/2.0/config/security/ */
        //$urlBuilder = UrlBuilderFactory::create('/', $this->signKey);

        //return $urlBuilder->getUrl($url);
    }
}
