<?php

// src/Command/TestEmailCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:test-mailing',description: 'Testing mail form')]

class TestEmailCommand extends Command
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (new Email())
            ->from('your-email@example.com')
            ->to('ugo.armer@gmail.com')
            ->subject('Mitzi Castillo Portfolio')
            ->text('This is a test email.');

        $this->mailer->send($email);

        $output->writeln('Test email sent successfully.');
        return Command::SUCCESS;
    }
}
