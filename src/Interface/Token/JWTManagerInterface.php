<?php

declare(strict_types=1);

namespace App\Interface\Token;

interface JWTManagerInterface
{
    public function __invoke(): void;
}
