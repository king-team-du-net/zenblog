<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Helper;

final class Timestampable
{
    /**
     * Generates a duration in "30 min" format.
     */
    public static function duration(int $duration): string
    {
        $minutes = round($duration / 60);

        if ($minutes < 60) {
            return $minutes.' min';
        }

        $hours = floor($minutes / 60);

        $minutes = str_pad((string) ($minutes - ($hours * 60)), 2, '0', \STR_PAD_LEFT);

        return "{$hours}h{$minutes}";
    }
}
