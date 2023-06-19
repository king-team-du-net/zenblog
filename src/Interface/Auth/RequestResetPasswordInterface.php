<?php

declare(strict_types=1);

namespace App\Interface\Auth;

use App\Entity\ResetPasswordRequest;

interface RequestResetPasswordInterface
{
    public function __invoke(ResetPasswordRequest $resetPasswordRequest): void;
}
