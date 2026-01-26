<?php

namespace App\Controller\Admin;

use App\Entity\Rating;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;

class RatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rating::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Notes')
            ->setPageTitle('new', 'Ajouter une note')
            ->setPageTitle('edit', 'Modifier une note')
            ->setPageTitle('detail', 'Détails de la note')
            ->setEntityLabelInSingular('Note')
            ->setEntityLabelInPlural('Notes')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['user.username', 'rubiksCube.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('user', 'Utilisateur'))
            ->add(EntityFilter::new('rubiksCube', 'Rubik\'s Cube'))
            ->add(NumericFilter::new('rating', 'Note'));
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
                ->setHelp('Utilisateur qui a donné la note'),
            AssociationField::new('rubiksCube', 'Rubik\'s Cube')
                ->setRequired(true)
                ->setHelp('Rubik\'s Cube noté'),
            IntegerField::new('rating', 'Note (1-5)')
                ->setRequired(true)
                ->setHelp('Note entre 1 et 5 étoiles')
                ->setFormTypeOption('attr', ['min' => 1, 'max' => 5]),
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
        ];
    }
}
