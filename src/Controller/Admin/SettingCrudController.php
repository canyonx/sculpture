<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SettingCrudController extends AbstractCrudController
{
    public function __construct(
        private SettingRepository $settingRepository
    ) {
    }
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInSingular('Paramètre')
            ->setEntityLabelInPlural('Paramètres')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier les paramètres du site');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('delete')
            ->disable('new')
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre (nom)'),
            TextField::new('subtitle', 'Sous-titre'),
            TextareaField::new('description', 'Description')
                ->hideOnIndex(),
            ImageField::new('logo', 'Logo')
                ->setBasePath('/uploads/images/')
                ->setUploadDir('public/uploads/images/')
                ->setUploadedFileNamePattern('logo.[extension]'),
            ImageField::new('background', 'Arrière-plan')
                ->setBasePath('/uploads/images/')
                ->setUploadDir('public/uploads/images/')
                ->setUploadedFileNamePattern('background.[extension]'),
        ];
    }

    public function settingExist(SettingRepository $settingRepository)
    {
        if ($settingRepository->findAll() ? true : false);
    }
}
