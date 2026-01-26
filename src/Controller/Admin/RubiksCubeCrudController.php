<?php

namespace App\Controller\Admin;

use App\Entity\RubiksCube;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class RubiksCubeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RubiksCube::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des Rubik\'s Cubes')
            ->setPageTitle('new', 'Ajouter un Rubik\'s Cube')
            ->setPageTitle('edit', 'Modifier un Rubik\'s Cube')
            ->setPageTitle('detail', 'Détails du Rubik\'s Cube')
            ->setEntityLabelInSingular('Rubik\'s Cube')
            ->setEntityLabelInPlural('Rubik\'s Cubes')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setSearchFields(['name', 'type', 'brand', 'description']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Nom'))
            ->add(TextFilter::new('type', 'Type'))
            ->add(TextFilter::new('brand', 'Marque'))
            ->add(ChoiceFilter::new('difficulty', 'Difficulté')
                ->setChoices([
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Expert' => 'Expert',
                ]));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Ajouter un Rubik\'s Cube');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom')
                ->setRequired(true)
                ->setHelp('Nom du Rubik\'s Cube'),
            TextField::new('type', 'Type')
                ->setRequired(true)
                ->setHelp('Ex: 2x2, 3x3, 4x4, Pyraminx, Megaminx, etc.'),
            TextField::new('brand', 'Marque')
                ->setRequired(false)
                ->setHelp('Marque du cube'),
            TextField::new('difficulty', 'Difficulté')
                ->setRequired(false)
                ->setHelp('Débutant, Intermédiaire ou Expert'),
            IntegerField::new('releaseYear', 'Année de sortie')
                ->setRequired(false)
                ->setHelp('Année de sortie du cube'),
            ImageField::new('imageUrl', 'Image')
                ->setBasePath('/')
                ->setUploadDir('public/uploads')
                ->setRequired(false)
                ->setHelp('URL de l\'image ou téléchargez une image'),
            TextareaField::new('description', 'Description')
                ->setRequired(false)
                ->setNumOfRows(5)
                ->setHelp('Description détaillée du Rubik\'s Cube'),
            AssociationField::new('comments', 'Commentaires')
                ->onlyOnDetail(),
            AssociationField::new('ratings', 'Notes')
                ->onlyOnDetail(),
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
        ];
    }
}
