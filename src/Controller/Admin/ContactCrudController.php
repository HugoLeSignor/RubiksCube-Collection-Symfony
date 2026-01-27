<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Message de Contact')
            ->setEntityLabelInPlural('Messages de Contact')
            ->setPageTitle('index', '📧 Messages de Contact')
            ->setPageTitle('detail', fn(Contact $contact) => sprintf('Message de %s', $contact->getName()))
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['name', 'email', 'subject', 'message'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        $replyAction = Action::new('reply', 'Répondre', 'fa fa-reply')
            ->linkToRoute('admin_contact_reply', function (Contact $contact): array {
                return ['id' => $contact->getId()];
            })
            ->displayIf(static function (Contact $contact) {
                return !$contact->isReplied();
            });

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $replyAction)
            ->add(Crud::PAGE_INDEX, $replyAction)
            ->disable(Action::NEW)
            ->disable(Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isRead', 'Lu'))
            ->add(BooleanFilter::new('isReplied', 'Répondu'))
            ->add(DateTimeFilter::new('createdAt', 'Date de création'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex();

        yield TextField::new('name', 'Nom')
            ->setColumns(6);

        yield EmailField::new('email', 'Email')
            ->setColumns(6);

        yield TextField::new('subject', 'Sujet')
            ->formatValue(function ($value) {
                $subjects = [
                    'question' => '❓ Question sur un cube',
                    'collection' => '📦 Ma collection',
                    'achat' => '🛒 Conseils d\'achat',
                    'technique' => '🧠 Technique de résolution',
                    'suggestion' => '💡 Suggestion',
                    'autre' => '📝 Autre',
                ];
                return $subjects[$value] ?? $value;
            });

        yield TextareaField::new('message', 'Message')
            ->hideOnIndex()
            ->setColumns(12);

        yield DateTimeField::new('createdAt', 'Reçu le')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->setColumns(4);

        yield BooleanField::new('isRead', 'Lu')
            ->setColumns(4)
            ->renderAsSwitch(true);

        yield BooleanField::new('isReplied', 'Répondu')
            ->setColumns(4)
            ->renderAsSwitch(true);

        if (Crud::PAGE_DETAIL === $pageName) {
            yield TextareaField::new('message', 'Message complet')
                ->setHelp('Contenu du message envoyé par l\'utilisateur')
                ->onlyOnDetail();
        }
    }

    #[Route('/admin/contact/{id}/reply', name: 'admin_contact_reply')]
    public function reply(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        AdminUrlGenerator $adminUrlGenerator
    ): Response {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            $this->addFlash('danger', 'Message introuvable.');
            return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Crud::PAGE_INDEX)->generateUrl());
        }

        if ($request->isMethod('POST')) {
            $replySubject = $request->request->get('reply_subject');
            $replyMessage = $request->request->get('reply_message');

            if (empty($replySubject) || empty($replyMessage)) {
                $this->addFlash('danger', 'Le sujet et le message sont requis.');
            } else {
                try {
                    // Envoyer l'email de réponse
                    $email = (new Email())
                        ->from('support@rubikscube-collection.com')
                        ->to($contact->getEmail())
                        ->subject($replySubject)
                        ->html($this->renderView('admin/contact_reply_email.html.twig', [
                            'contact' => $contact,
                            'replyMessage' => $replyMessage,
                        ]));

                    $mailer->send($email);

                    // Marquer comme répondu et lu
                    $contact->setIsReplied(true);
                    $contact->setIsRead(true);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre réponse a été envoyée avec succès à ' . $contact->getEmail());

                    return $this->redirect(
                        $adminUrlGenerator
                            ->setController(self::class)
                            ->setAction(Crud::PAGE_DETAIL)
                            ->setEntityId($id)
                            ->generateUrl()
                    );
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
                }
            }
        }

        return $this->render('admin/contact_reply.html.twig', [
            'contact' => $contact,
        ]);
    }
}
