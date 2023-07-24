<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Utils;

use Symfony\Component\Validator\Constraint;

class AttachmentExist extends Constraint
{
    public string $message = 'No attachment matches id {{ id }}';
}
