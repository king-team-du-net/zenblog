<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Auth;

use App\Entity\ResetPasswordRequest;

interface ResetPasswordInterface
{
    public function __invoke(ResetPasswordRequest $resetPasswordRequest): void;
}
