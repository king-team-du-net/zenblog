<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ContactService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\ContactRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use App\Repository\User\AdministratorRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name: 'app:send-contact',
    description: 'Add a short description for your command',
)]
final class SendContactCommand extends Command
{
    public function __construct(
        private readonly ContactRepository $contactRepository,
        private readonly UserRepository $userRepository,
        private readonly ContactService $contactService,
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer
    ) {
        parent::__construct('app:send-contact');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $toSend = $this->contactRepository->findBy(['isSend' => false]);
        $address = new Address(
            $this->userRepository->getAdministrator()->getEmail(),
            $this->userRepository->getAdministrator()->getFullName()
        );

        foreach ($toSend as $mail) {
            $message = (new Email())
                ->from($mail->getEmail())
                ->to($address)
                ->subject($this->translator->trans('New message from ').$mail->getFullname())
                ->text($mail->getMessage())
            ;

            $this->mailer->send($message);
            $this->contactService->isSend($mail);
        }

        return Command::SUCCESS;
    }
}
