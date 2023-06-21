<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:list-users',
    description: 'Lists all the existing users',
    aliases: ['app:users']
)]
final class ListUsersCommand extends Command
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $emailSender,
        private readonly UserRepository $users
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setHelp(<<<'HELP'
                The <info>%command.name%</info> command lists all the users registered in the application:

                <info>php %command.full_name%</info>

                By default the command only displays the 50 most recent users. Set the number of
                results to display with the <comment>--max-results</comment> option:

                <info>php %command.full_name%</info> <comment>--max-results=2000</comment>

                In addition to displaying the user list, you can also send this information to
                the email address specified in the <comment>--send-to</comment> option:

                <info>php %command.full_name%</info> <comment>--send-to=contact@yourdomain.com</comment>
                HELP
            )

            ->addOption('max-results', null, InputOption::VALUE_OPTIONAL, 'Limits the number of users listed', 50)
            ->addOption('send-to', null, InputOption::VALUE_OPTIONAL, 'If set, the result is sent to the given email address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var int|null $maxResults */
        $maxResults = $input->getOption('max-results');

        // Use ->findBy() instead of ->findAll() to allow result sorting and limiting
        $allUsers = $this->users->findBy([], ['id' => 'DESC'], $maxResults);

        $createUserArray = static function (User $user) {
            return [
                $user->getId(),
                $user->getNickname(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                implode(', ', $user->getRoles()),
            ];
        };

        $usersAsPlainArrays = array_map($createUserArray, $allUsers);

        $bufferedOutput = new BufferedOutput();
        $io = new SymfonyStyle($input, $bufferedOutput);
        $io->table(
            ['ID', 'Nickname', 'Firstname', 'Lastname', 'Email', 'Roles'],
            $usersAsPlainArrays
        );

        $usersAsATable = $bufferedOutput->fetch();
        $output->write($usersAsATable);

        /** @var string|null $email */
        $email = $input->getOption('send-to');

        if (null !== $email) {
            $this->sendReport($usersAsATable, $email);
        }

        return Command::SUCCESS;
    }

    /**
     * Sends the given $contents to the $recipient email address.
     */
    private function sendReport(string $contents, string $recipient): void
    {
        $email = (new Email())
            ->from($this->emailSender)
            ->to($recipient)
            ->subject(sprintf('app:list-users report (%s)', date('Y-m-d H:i:s')))
            ->text($contents)
        ;

        $this->mailer->send($email);
    }
}
