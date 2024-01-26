<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private UserRepository $userRepository
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN')
            // ->overrideTemplate('crud/index', 'admin/profil.html.twig')
            ->showEntityActionsInlined()
            ->setEntityLabelInSingular('Profil')
            ->setEntityLabelInPlural('Profils')
            ->setPageTitle('edit', 'Modifier mon profil')
            ->setPageTitle('index', 'Mon Profil');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('new')
            ->disable('delete')
            ->remove('edit', Action::SAVE_AND_CONTINUE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Description'),
            TextEditorField::new('about', 'A propos')
                ->hideOnIndex(),
            ImageField::new('avatar', 'Photo de Profil')
                ->setBasePath('/uploads/images/')
                ->setUploadDir('public/uploads/images/')
                ->setUploadedFileNamePattern('avatar.[extension]'),

            FormField::addTab('Contact'),
            TextField::new('address', 'Adresse'),
            EmailField::new('email', 'Email'),
            TelephoneField::new('phone', 'Téléphone'),
            UrlField::new('facebook', 'Lien Facebook')
                ->hideOnIndex(),
            UrlField::new('instagram', 'Lien Instagram')
                ->hideOnIndex(),

            FormField::addTab('Changer de mot de passe'),
            TextField::new('username', 'Nom d\'utilisateur')
                ->setDisabled(),
            TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)
                ->setDisabled()
                ->onlyOnForms(),
            TextField::new('plainPassword', 'Nouveau mot de passe')
                ->onlyOnForms()
                ->setColumns(2)
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'attr' => array('placeholder' => ''),
                    'first_options' => ['label' => 'Nouveau mot de passe', 'hash_property_path' => 'password'],
                    'second_options' => ['label' => 'Confirmer le mot de passe'],
                    'empty_data' => true,
                    'mapped' => false,
                    'constraints' => [
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Aller, on trouve un mot de passe un peu plus long !'
                        ])
                    ],
                ]),

            // Debug
            // TextField::new('password')->onlyOnIndex(),
            // TextField::new('plainPassword')->onlyOnIndex(),

        ];
    }
}
