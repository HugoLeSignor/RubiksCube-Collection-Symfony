<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestEmailController extends AbstractController
{
    #[Route('/test-email', name: 'app_test_email')]
    public function testEmail(MailerInterface $mailer): Response
    {
        try {
            $email = (new Email())
                ->from('test@rubikscube-collection.com')
                ->to('destinataire@example.com')
                ->subject('🧪 Test d\'envoi d\'email')
                ->html('<h1>Test réussi ! ✅</h1><p>Si vous voyez cet email, le système d\'envoi fonctionne parfaitement.</p>');

            $mailer->send($email);

            return new Response('
                <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                max-width: 800px;
                                margin: 50px auto;
                                padding: 20px;
                                background: #f5f5f5;
                            }
                            .success {
                                background: #d4edda;
                                border: 2px solid #28a745;
                                color: #155724;
                                padding: 20px;
                                border-radius: 8px;
                                text-align: center;
                            }
                            .info {
                                background: #d1ecf1;
                                border: 1px solid #0c5460;
                                color: #0c5460;
                                padding: 15px;
                                border-radius: 8px;
                                margin-top: 20px;
                            }
                            a {
                                color: #007bff;
                                text-decoration: none;
                            }
                            .btn {
                                display: inline-block;
                                background: #007bff;
                                color: white;
                                padding: 10px 20px;
                                border-radius: 5px;
                                margin-top: 20px;
                                text-decoration: none;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="success">
                            <h1>✅ Email envoyé avec succès !</h1>
                            <p>L\'email de test a été envoyé vers Mailtrap.</p>
                        </div>
                        
                        <div class="info">
                            <h2>📬 Où voir l\'email ?</h2>
                            <ol>
                                <li>Allez sur <a href="https://mailtrap.io/signin" target="_blank">https://mailtrap.io/signin</a></li>
                                <li>Connectez-vous avec vos identifiants Mailtrap</li>
                                <li>Cliquez sur votre inbox (sandbox)</li>
                                <li>Vous devriez voir l\'email de test "🧪 Test d\'envoi d\'email"</li>
                            </ol>
                            
                            <h3>📋 Configuration actuelle :</h3>
                            <pre>MAILER_DSN="smtp://05e83512e7eb74:***@sandbox.smtp.mailtrap.io:2525"</pre>
                            
                            <p><strong>Note :</strong> Les emails ne sont PAS envoyés réellement, mais capturés par Mailtrap pour les tests.</p>
                        </div>
                        
                        <div style="text-align: center;">
                            <a href="/" class="btn">← Retour à l\'accueil</a>
                            <a href="/admin" class="btn">🔧 Admin</a>
                            <a href="/contact" class="btn">📧 Contact</a>
                        </div>
                    </body>
                </html>
            ');
        } catch (\Exception $e) {
            return new Response('
                <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                max-width: 800px;
                                margin: 50px auto;
                                padding: 20px;
                                background: #f5f5f5;
                            }
                            .error {
                                background: #f8d7da;
                                border: 2px solid #dc3545;
                                color: #721c24;
                                padding: 20px;
                                border-radius: 8px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="error">
                            <h1>❌ Erreur d\'envoi</h1>
                            <p><strong>Message d\'erreur :</strong></p>
                            <pre>' . htmlspecialchars($e->getMessage()) . '</pre>
                            
                            <h3>🔧 Vérifications à faire :</h3>
                            <ul>
                                <li>Vérifier que MAILER_DSN est bien configuré dans .env</li>
                                <li>Vérifier que les identifiants Mailtrap sont corrects</li>
                                <li>Vider le cache : php bin/console cache:clear</li>
                            </ul>
                        </div>
                        <div style="text-align: center; margin-top: 20px;">
                            <a href="/" style="color: #007bff;">← Retour à l\'accueil</a>
                        </div>
                    </body>
                </html>
            ', 500);
        }
    }
}
