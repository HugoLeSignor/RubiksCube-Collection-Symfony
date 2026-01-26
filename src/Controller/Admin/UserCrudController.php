<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;
    private RequestStack $requestStack;

    public function __construct(UserPasswordHasherInterface $passwordHasher, RequestStack $requestStack)
    {
        $this->passwordHasher = $passwordHasher;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Utilisateurs')
            ->setPageTitle('new', 'Ajouter un utilisateur')
            ->setPageTitle('edit', 'Modifier un utilisateur')
            ->setPageTitle('detail', 'Détails de l\'utilisateur')
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['username', 'email']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('username', 'Nom d\'utilisateur'))
            ->add(TextFilter::new('email', 'Email'))
            ->add(ChoiceFilter::new('roles', 'Rôles')
                ->setChoices([
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ]));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer')
                    ->setIcon('fa fa-trash')
                    ->setCssClass('btn btn-danger');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('username', 'Nom d\'utilisateur')
                ->setRequired(true),
            EmailField::new('email', 'Email')
                ->setRequired(true),
        ];

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = PasswordField::new('password', 'Mot de passe')
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->setHelp($pageName === Crud::PAGE_EDIT ? 'Laissez vide pour ne pas modifier le mot de passe' : 'Minimum 6 caractères')
                ->setFormTypeOption('mapped', false);
        }

        $fields[] = ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
            ])
            ->allowMultipleChoices()
            ->setRequired(true)
            ->setHelp('Sélectionnez les rôles de l\'utilisateur');

        $fields[] = DateTimeField::new('createdAt', 'Date d\'inscription')
            ->hideOnForm()
            ->setFormat('dd/MM/yyyy HH:mm');

        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $entityInstance;
        
        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $formData = $request->request->all();
            $userData = $formData['User'] ?? [];
            
            if (isset($userData['password']) && !empty($userData['password'])) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
                $user->setPassword($hashedPassword);
            }
        }

        parent::persistEntity($entityManager, $user);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $entityInstance;
        
        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $formData = $request->request->all();
            $userData = $formData['User'] ?? [];
            
            if (isset($userData['password']) && !empty($userData['password'])) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
                $user->setPassword($hashedPassword);
            }
        }

        parent::updateEntity($entityManager, $user);
    }
}
