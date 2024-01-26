<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use App\Controller\Admin\Fields\MultipleImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ProductCrudController extends AbstractCrudController
{
    public function __construct(
        private UploadService $uploader,
        private SluggerInterface $slugger,
        private CacheManager $cacheManager
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sculpture')
            ->setEntityLabelInPlural('Sculptures')
            ->setPageTitle('index', 'Mes Sculptures')
            ->setSearchFields(['name', 'year'])
            ->setDefaultSort(['year' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove('new', Action::SAVE_AND_ADD_ANOTHER)
            ->remove('edit', Action::SAVE_AND_CONTINUE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            // TextField::new('slug')->onlyOnIndex(),
            TextEditorField::new('description', 'Description')
                ->hideOnIndex(),
            TextField::new('material', 'Matière'),
            TextField::new('size', 'Dim. h x l x p'),
            IntegerField::new('year', 'Année'),

            // Custom Field Upload galery on edit
            MultipleImageField::new('imageFile', 'Ajouter des images')
                ->onlyOnForms(),

            // Voir image-1 onIndex
            ImageField::new('picture', 'Image')
                ->setBasePath('uploads/images/')
                ->setUploadDir('public/uploads/images/')
                ->onlyOnIndex(),

            BooleanField::new('is360', 'Vue 360'),

            AssociationField::new('images')
                ->onlyOnIndex(),

            // Debug
            // TextField::new('picture')
            //     ->onlyOnIndex(),
            // TextField::new('slug')
            //     ->onlyOnIndex(),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $product): void
    {
        if ($product->getImageFile()) {

            // Créer le slug, Définir le répertoire
            $product->computeSlug($this->slugger);
            $directory = $this->getParameter('images_dir') . $product->getSlug() . '/';

            // Uploader les images dans le nouveau répertoire, Définir l'image du produit
            $this->uploader->uploadImages($directory, $product);
            $product->setPicture($product->getSlug() . '/' . $product->getSlug() . '-1.jpg');
        }

        $entityManager->persist($product);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $product): void
    {
        if ($product->getImageFile()) {

            // Définir le répertoire, Supprimer le répertoire
            $directory = $this->getParameter('images_dir') . $product->getSlug() . '/';
            $this->uploader->deleteImages($directory, $product);
            $this->cacheManager->remove($directory);

            // Modifier le slug, Nouveau nom de répertoire
            $product->computeSlug($this->slugger);
            $directory = $this->getParameter('images_dir') . $product->getSlug() . '/';

            // Uploader les images dans le nouveau répertoire, Définir l'image du produit
            $this->uploader->uploadImages($directory, $product);
            $product->setPicture($product->getSlug() . '/' . $product->getSlug() . '-1.jpg');
        }

        $entityManager->persist($product);
        $entityManager->flush();
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $product): void
    {
        // Définir le répertoire, Supprimer le répertoire
        $directory = $this->getParameter('images_dir') . $product->getSlug() . '/';

        if (file_exists($directory)) {
            $this->uploader->deleteImages($directory, $product);
            $this->cacheManager->remove($directory);
        }

        $entityManager->remove($product);
        $entityManager->flush();
    }
}
