<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Utils;

use App\Entity\Image\Attachment;

class AttachmentNoExist extends Attachment
{
    public function __construct(int $expectedId)
    {
        $this->id = $expectedId;
    }
}
