<?php

namespace App\Controller\Admin;

use App\Entity\UserCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class UserCollectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserCollection::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Collections Utilisateurs')
            ->setPageTitle('new', 'Ajouter à une collection')
            ->setPageTitle('edit', 'Modifier une collection')
            ->setPageTitle('detail', 'Détails de la collection')
            ->setEntityLabelInSingular('Collection')
            ->setEntityLabelInPlural('Collections')
            ->setDefaultSort(['addedAt' => 'DESC'])
            ->setSearchFields(['user.username', 'rubiksCube.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('user', 'Utilisateur'))
            ->add(EntityFilter::new('rubiksCube', 'Rubik\'s Cube'))
            ->add(TextFilter::new('condition', 'État'));
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
                ->setHelp('Propriétaire de la collection'),
            AssociationField::new('rubiksCube', 'Rubik\'s Cube')
                ->setRequired(true)
                ->setHelp('Rubik\'s Cube dans la collection'),
            ChoiceField::new('condition', 'État')
                ->setChoices([
                    'Neuf' => 'Neuf',
                    'Très bon' => 'Très bon',
                    'Bon' => 'Bon',
                    'Usé' => 'Usé',
                ])
                ->setRequired(false)
                ->setHelp('État du cube'),
            NumberField::new('purchasePrice', 'Prix d\'achat')
                ->setRequired(false)
                ->setHelp('Prix d\'achat en euros')
                ->setNumDecimals(2),
            TextareaField::new('personalNote', 'Note personnelle')
                ->setRequired(false)
                ->setNumOfRows(3)
                ->setHelp('Note personnelle sur ce cube'),
            DateTimeField::new('addedAt', 'Date d\'ajout')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
        ];
    }
}
