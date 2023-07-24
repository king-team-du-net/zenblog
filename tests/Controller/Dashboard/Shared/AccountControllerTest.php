<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Tests\Controller\Dashboard\Shared;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for the controllers defined inside the AccountController used
 * for managing the current logged user.
 *
 * See https://symfony.com/doc/current/testing.html#functional-tests
 *
 * Whenever you test resources protected by a firewall, consider using the
 * technique explained in:
 * https://symfony.com/doc/current/testing/http_authentication.html
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ ./vendor/bin/phpunit
 */
final class AccountControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $this->assertResponseRedirects(
            'http://localhost/en/signin',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function getUrlsForAnonymousUsers(): \Generator
    {
        yield ['GET', '/en/%website_dashboard_path%/account/settings/update-profile'];
        yield ['GET', '/en/%website_dashboard_path%/account/settings/update-avatar'];
        yield ['GET', '/en/%website_dashboard_path%/account/settings/update-password'];
    }

    public function testUpdateProfile(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneByNickname('administrator');

        $newUserEmail = 'williamson-cameron@yourdomain.com';

        $client->loginUser($user);

        $client->request('GET', '/en/%website_dashboard_path%/account/settings/update-profile');
        $client->submitForm('Save changes', [
            'user[email]' => $newUserEmail,
        ]);

        $this->assertResponseRedirects('/en/%website_dashboard_path%/account/settings/update-profile', Response::HTTP_FOUND);

        /** @var User $user */
        $user = $userRepository->findOneByEmail($newUserEmail);

        $this->assertNotNull($user);
        $this->assertSame($newUserEmail, $user->getEmail());
    }

    public function testUpdatePassword(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneByNickname('administrator');

        $newUserPassword = 'new-password';

        $client->loginUser($user);
        $client->request('GET', '/en/%website_dashboard_path%/account/settings/update-password');
        $client->submitForm('Save changes', [
            'change_password[currentPassword]' => 'administrator',
            'change_password[newPassword][first]' => $newUserPassword,
            'change_password[newPassword][second]' => $newUserPassword,
        ]);

        $this->assertResponseRedirects();
        $this->assertStringStartsWith(
            '/en/signout',
            $client->getResponse()->headers->get('Location') ?? '',
            'Changing password logout the user.'
        );
    }
}
