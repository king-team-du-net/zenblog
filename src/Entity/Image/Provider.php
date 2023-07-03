<?php

declare(strict_types=1);

namespace App\Entity\Image;

enum Provider: string
{
    case Youtube = 'youtube';
    case Vimeo = 'vimeo';
    case Dailymotion = 'dailymotion';

    public static function isValid(string $url): bool
    {
        return 1 === preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=[a-zA-Z0-9_-]{11}$/', $url)
            || 1 === preg_match('/^https:\/\/vimeo\.com\/[0-9]{9}$/', $url)
            || 1 === preg_match('/^https:\/\/www\.dailymotion\.com\/video\/[a-zA-Z0-9]{7}$/', $url)
        ;
    }

    public static function fromUrl(string $url): self
    {
        if (1 === preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=.+$/', $url)) {
            return self::Youtube;
        }

        if (1 === preg_match('/^https:\/\/vimeo\.com\/.+$/', $url)) {
            return self::Vimeo;
        }

        return self::Dailymotion;
    }

    public function getId(string $url): string
    {
        preg_match(
            match ($this) {
                self::Youtube => '/^https:\/\/www\.youtube\.com\/watch\?v=(.+)$/',
                self::Vimeo => '/^https:\/\/vimeo\.com\/(.+)$/',
                self::Dailymotion => '/^https:\/\/www\.dailymotion\.com\/video\/(.+)$/',
            },
            $url,
            $matches
        );

        /** @var string $id */
        [, $id] = $matches;

        return $id;
    }
}
