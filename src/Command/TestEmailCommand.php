<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:test-email',
    description: 'Teste l\'envoi d\'un email via Mailtrap',
)]
class TestEmailCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🧪 Test d\'envoi d\'email');

        try {
            $email = (new Email())
                ->from('test@rubikscube-collection.com')
                ->to('destinataire@example.com')
                ->subject('🧪 Test d\'envoi d\'email depuis la console')
                ->html('<h1>✅ Test réussi !</h1><p>Si vous voyez cet email dans Mailtrap, le système fonctionne parfaitement.</p>');

            $this->mailer->send($email);

            $io->success('Email envoyé avec succès vers Mailtrap !');
            $io->note([
                'Vérifiez votre inbox Mailtrap :',
                '1. Allez sur https://mailtrap.io',
                '2. Connectez-vous',
                '3. Cliquez sur votre inbox',
                '4. Vous devriez voir l\'email "🧪 Test d\'envoi d\'email depuis la console"',
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Erreur lors de l\'envoi de l\'email');
            $io->text('Message d\'erreur : ' . $e->getMessage());

            $io->section('🔧 Vérifications à faire :');
            $io->listing([
                'Vérifier MAILER_DSN dans .env',
                'Vérifier que les identifiants Mailtrap sont corrects',
                'Vérifier la connexion internet',
                'Vider le cache : php bin/console cache:clear',
            ]);

            return Command::FAILURE;
        }
    }
}
