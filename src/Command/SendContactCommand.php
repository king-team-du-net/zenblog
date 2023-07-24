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

use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\ContactService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
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
                ->subject($this->translator->trans('command.message_subject').$mail->getFullname())
                ->text($mail->getMessage())
            ;

            $this->mailer->send($message);
            $this->contactService->isSend($mail);
        }

        return Command::SUCCESS;
    }
}
