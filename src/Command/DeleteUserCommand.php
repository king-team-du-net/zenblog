<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name: 'app:delete-user',
    description: 'Deletes users from the database'
)]
final class DeleteUserCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator,
        private readonly Validator $validator,
        private readonly UserRepository $users,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nickname', InputArgument::REQUIRED, 'The nickname of an existing user')
            ->setHelp(<<<'HELP'
                The <info>%command.name%</info> command deletes users from the database:

                <info>php %command.full_name%</info> <comment>nickname</comment>

                If you omit the argument, the command will ask you to
                provide the missing value:

                <info>php %command.full_name%</info>
                HELP
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('nickname')) {
            return;
        }

        $this->io->title('Delete User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:delete-user nickname',
            ' $ symfony console app:delete-user nickname',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
            '',
        ]);

        $nickname = $this->io->ask('Nickname', null, [$this->validator, 'validateNickname']);
        $input->setArgument('nickname', $nickname);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string|null $nickname */
        $nickname = $input->getArgument('nickname');
        $nickname = $this->validator->validateNickname($nickname);

        /** @var User|null $user */
        $user = $this->users->findOneByNickname($nickname);

        if (null === $user) {
            throw new RuntimeException(sprintf('User with nickname "%s" not found.', $nickname));
        }

        $userId = $user->getId();

        $this->em->remove($user);
        $this->em->flush();

        $userNickname = $user->getNickname();
        $userEmail = $user->getEmail();

        $this->io->success(sprintf('User "%s" (ID: %d, email: %s) was successfully deleted.', $userNickname, $userId, $userEmail));
        $this->logger->info('User "{nickname}" (ID: {id}, email: {email}) was successfully deleted.', ['nickname' => $userNickname, 'id' => $userId, 'email' => $userEmail]);

        return Command::SUCCESS;
    }
}
