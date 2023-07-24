<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Tests\Utils;

use App\Utils\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    public function testValidateNickname(): void
    {
        $test = 'nickname';

        $this->assertSame($test, $this->validator->validateNickname($test));
    }

    public function testValidateNicknameEmpty(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The nickname can not be empty.');
        $this->validator->validateNickname(null);
    }

    public function testValidateNicknameInvalid(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The nickname must contain only lowercase latin characters and underscores.');
        $this->validator->validateNickname('INVALID');
    }

    public function testValidatePassword(): void
    {
        $test = 'password';

        $this->assertSame($test, $this->validator->validatePassword($test));
    }

    public function testValidatePasswordEmpty(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The password can not be empty.');
        $this->validator->validatePassword(null);
    }

    public function testValidatePasswordInvalid(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The password must be at least 6 characters long.');
        $this->validator->validatePassword('12345');
    }

    public function testValidateEmail(): void
    {
        $test = '@';

        $this->assertSame($test, $this->validator->validateEmail($test));
    }

    public function testValidateEmailEmpty(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The email can not be empty.');
        $this->validator->validateEmail(null);
    }

    public function testValidateEmailInvalid(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The email should look like a real email.');
        $this->validator->validateEmail('invalid');
    }

    public function testValidateFirstname(): void
    {
        $test = 'firstname';

        $this->assertSame($test, $this->validator->validateFirstname($test));
    }

    public function testValidateFirstnameEmpty(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The firstname can not be empty.');
        $this->validator->validateFirstname(null);
    }


    public function testValidateLastname(): void
    {
        $test = 'lastname';

        $this->assertSame($test, $this->validator->validateLastname($test));
    }

    public function testValidateLastnameEmpty(): void
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('The lastname can not be empty.');
        $this->validator->validateFirstname(null);
    }
}
