<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\User\EmailVerification;

final class TooManyEmailChangeException extends \Exception
{
    public function __construct(public EmailVerification $emailVerification)
    {
        parent::__construct();
    }
}
