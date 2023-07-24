<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use function Symfony\Component\String\u;

class Validator
{
    public function validateFirstname(?string $firstname): string
    {
        if (empty($firstname)) {
            throw new InvalidArgumentException('The firstname can not be empty.');
        }

        return $firstname;
    }

    public function validateLastname(?string $lastname): string
    {
        if (empty($lastname)) {
            throw new InvalidArgumentException('The lastname can not be empty.');
        }

        return $lastname;
    }

    public function validateNickname(?string $nickname): string
    {
        if (empty($nickname)) {
            throw new InvalidArgumentException('The nickname can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $nickname)) {
            throw new InvalidArgumentException('The nickname must contain only lowercase latin characters and underscores.');
        }

        return $nickname;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (u($plainPassword)->trim()->length() < 8) {
            throw new InvalidArgumentException('The password must be at least 8 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (null === u($email)->indexOf('@')) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }
}
