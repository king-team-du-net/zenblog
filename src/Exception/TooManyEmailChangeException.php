<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Exception;

use App\Entity\User\EmailVerification;

final class TooManyEmailChangeException extends \Exception
{
    public function __construct(public EmailVerification $emailVerification)
    {
        parent::__construct();
    }
}
