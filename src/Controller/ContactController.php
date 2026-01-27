<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $success = false;
        $errors = [];

        if ($request->isMethod('POST')) {
            $name = trim($request->request->get('name', ''));
            $email = trim($request->request->get('email', ''));
            $subject = trim($request->request->get('subject', ''));
            $message = trim($request->request->get('message', ''));

            if (empty($name)) {
                $errors['name'] = 'Le nom est requis.';
            }

            if (empty($email)) {
                $errors['email'] = 'L\'email est requis.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email n\'est pas valide.';
            }

            if (empty($subject)) {
                $errors['subject'] = 'Le sujet est requis.';
            }

            if (empty($message)) {
                $errors['message'] = 'Le message est requis.';
            } elseif (strlen($message) < 10) {
                $errors['message'] = 'Le message doit contenir au moins 10 caractères.';
            }

            if (empty($errors)) {
                // Créer et sauvegarder le contact en base de données
                $contact = new Contact();
                $contact->setName($name);
                $contact->setEmail($email);
                $contact->setSubject($subject);
                $contact->setMessage($message);

                $entityManager->persist($contact);
                $entityManager->flush();

                // Envoyer l'email de confirmation à l'utilisateur
                try {
                    $confirmationEmail = (new Email())
                        ->from('noreply@rubikscube-collection.com')
                        ->to($email)
                        ->subject('Confirmation de réception de votre message')
                        ->html($this->renderView('contact/confirmation_email.html.twig', [
                            'name' => $name,
                            'subject' => $subject,
                            'message' => $message,
                        ]));

                    $mailer->send($confirmationEmail);

                    // Envoyer une notification à l'admin
                    $adminEmail = (new Email())
                        ->from('noreply@rubikscube-collection.com')
                        ->to('admin@rubikscube-collection.com')
                        ->subject('Nouveau message de contact : ' . $subject)
                        ->html($this->renderView('contact/admin_notification_email.html.twig', [
                            'name' => $name,
                            'email' => $email,
                            'subject' => $subject,
                            'message' => $message,
                            'contactId' => $contact->getId(),
                        ]));

                    $mailer->send($adminEmail);

                    $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais. Un email de confirmation vous a été envoyé.');
                } catch (\Exception $e) {
                    $this->addFlash('warning', 'Votre message a été enregistré mais l\'email de confirmation n\'a pas pu être envoyé.');
                }

                return $this->redirectToRoute('app_contact');
            }
        }

        return $this->render('contact/index.html.twig', [
            'errors' => $errors,
        ]);
    }
}
