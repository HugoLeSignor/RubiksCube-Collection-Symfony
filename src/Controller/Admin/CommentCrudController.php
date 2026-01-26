<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Commentaires')
            ->setPageTitle('new', 'Ajouter un commentaire')
            ->setPageTitle('edit', 'Modifier un commentaire')
            ->setPageTitle('detail', 'Détails du commentaire')
            ->setEntityLabelInSingular('Commentaire')
            ->setEntityLabelInPlural('Commentaires')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['content', 'user.username', 'rubiksCube.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('content', 'Contenu'))
            ->add(EntityFilter::new('user', 'Utilisateur'))
            ->add(EntityFilter::new('rubiksCube', 'Rubik\'s Cube'));
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
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user', 'Utilisateur')
                ->setRequired(true)
                ->setHelp('Utilisateur qui a écrit le commentaire'),
            AssociationField::new('rubiksCube', 'Rubik\'s Cube')
                ->setRequired(true)
                ->setHelp('Rubik\'s Cube concerné'),
            TextareaField::new('content', 'Commentaire')
                ->setRequired(true)
                ->setNumOfRows(5)
                ->setHelp('Contenu du commentaire'),
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
            DateTimeField::new('updatedAt', 'Dernière modification')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm')
                ->hideOnIndex(),
        ];
    }
}
