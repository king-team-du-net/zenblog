<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Utils\Validator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Creates users and stores them in the database'
)]
final class AddUserCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly Validator $validator,
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
            ->setHelp($this->getCommandHelp())

            ->addArgument('nickname', InputArgument::OPTIONAL, 'The nickname of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'The firstname of the new user')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'The lastname of the new user')

            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('nickname') && null !== $input->getArgument('password') && null !== $input->getArgument('email') && null !== $input->getArgument('firstname') && null !== $input->getArgument('lastname')) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-user nickname password email@example.com',
            ' $ symfony console app:add-user nickname password email@example.com',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        $nickname = $input->getArgument('nickname');
        if (null !== $nickname) {
            $this->io->text(' > <info>Nickname</info>: '.$nickname);
        } else {
            $nickname = $this->io->ask('Nickname', null, [$this->validator, 'validateNickname']);
            $input->setArgument('nickname', $nickname);
        }

        /** @var string|null $password */
        $password = $input->getArgument('password');

        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: '.u('*')->repeat(u($password)->length()));
        } else {
            $password = $this->io->askHidden('Password (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }

        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: '.$email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }

        $firstname = $input->getArgument('firstname');
        if (null !== $firstname) {
            $this->io->text(' > <info>Firstname</info>: '.$firstname);
        } else {
            $firstname = $this->io->ask('Firstname', null, [$this->validator, 'validateFirstname']);
            $input->setArgument('firstname', $firstname);
        }

        $lastname = $input->getArgument('lastname');
        if (null !== $lastname) {
            $this->io->text(' > <info>Lastname</info>: '.$lastname);
        } else {
            $lastname = $this->io->ask('Lastname', null, [$this->validator, 'validateLastname']);
            $input->setArgument('lastname', $lastname);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        /** @var string $nickname */
        $nickname = $input->getArgument('nickname');

        /** @var string $plainPassword */
        $plainPassword = $input->getArgument('password');

        /** @var string $email */
        $email = $input->getArgument('email');

        /** @var null|string $firstname */
        $firstname = $input->getArgument('firstname');

        /** @var null|string $lastname */
        $lastname = $input->getArgument('lastname');

        $isAdmin = $input->getOption('admin');

        $this->validateUserData($nickname, $plainPassword, $email, $firstname, $lastname);

        $user = new User;
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setNickname($nickname);
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? User::ADMIN : User::DEFAULT]);

        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $this->io->success(sprintf('%s was successfully created: %s (%s)', $isAdmin ? 'Administrator user' : 'User', $user->getNickname(), $user->getEmail()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    private function validateUserData(string $nickname, string $plainPassword, string $email, ?string $firstname, ?string $lastname): void
    {
        $existingUser = $this->users->findOneBy(['nickname' => $nickname]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" nickname.', $nickname));
        }

        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
        $this->validator->validateFirstname($firstname);
        $this->validator->validateLastname($lastname);

        $existingEmail = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
            The <info>%command.name%</info> command creates new users and saves them in the database:

            <info>php %command.full_name%</info> <comment>nickname password email</comment>

            By default the command creates regular users. To create administrator users,
            add the <comment>--admin</comment> option:

            <info>php %command.full_name%</info> nickname password email <comment>--admin</comment>

            If you omit any of the three required arguments, the command will ask you to
            provide the missing values:

            # command will ask you for the email
            <info>php %command.full_name%</info> <comment>nickname password</comment>

            # command will ask you for the email and password
            <info>php %command.full_name%</info> <comment>nickname</comment>

            # command will ask you for all arguments
            <info>php %command.full_name%</info>
            HELP;
    }
}
