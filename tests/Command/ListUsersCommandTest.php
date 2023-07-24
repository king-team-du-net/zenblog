<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Tests\Command;

use App\Command\ListUsersCommand;

final class ListUsersCommandTest extends AbstractCommandTest
{
    /**
     * @dataProvider maxResultsProvider
     *
     * This test verifies the amount of data is right according to the given parameter max results.
     */
    public function testListUsers(int $maxResults): void
    {
        $tester = $this->executeCommand(
            ['--max-results' => $maxResults]
        );

        $emptyDisplayLines = 5;
        $this->assertSame($emptyDisplayLines + $maxResults, mb_substr_count($tester->getDisplay(), "\n"));
    }

    public function maxResultsProvider(): \Generator
    {
        yield [1];
        yield [2];
    }

    public function testItSendsNoEmailByDefault(): void
    {
        $this->executeCommand([]);

        $this->assertEmailCount(0);
    }

    public function testItSendsAnEmailIfOptionProvided(): void
    {
        $this->executeCommand(['--send-to' => 'bob.doe@yourdomain.com']);

        $this->assertEmailCount(1);
    }

    protected function getCommandFqcn(): string
    {
        return ListUsersCommand::class;
    }
}
