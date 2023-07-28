<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Event\Content;

use App\Entity\Setting;

final class SettingUpdatedEvent
{
    public function __construct(private readonly Setting $setting)
    {
    }

    public function getSetting(): Setting
    {
        return $this->setting;
    }
}
