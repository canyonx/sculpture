<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Product;
use App\Service\ImageOptimizerService;
use Doctrine\ORM\EntityManagerInterface;

class UploadService
{

    public function __construct(
        private EntityManagerInterface $em,
        private ImageOptimizerService $imageOptimizer,
    ) {
    }

    // Supprimer les images du dossier
    // Supprimer les images du Product
    // Supprimer le dossier
    public function deleteImages(
        string $directory,
        Product $product
    ): void {
        // Supprime les anciennes images 
        foreach ($product->getImages() as $oldImage) {
            @unlink($directory . $oldImage->getName());
            $product->removeImage($oldImage);
        }
        // Supprime le dossier
        @rmdir($directory);
    }


    // Uploader plusieurs images dans un nouveau dossier
    public function uploadImages(
        string $directory,
        Product $product
    ): void {
        // Vérifie l'existance du dossier ou le crée
        if (!file_exists($directory)) {
            @mkdir($directory);
        }

        // Traitement des fichiers et enregistrement
        foreach ($product->getImageFile() as $k => $file) {
            $newFileName = $product->getSlug() . '-' . $k + 1 . '.' . $file->getClientOriginalExtension();

            $image = new Image();
            $image
                ->setProduct($product)
                ->setName($newFileName)
                ->upload($directory, $newFileName, $file);


            $this->imageOptimizer->resize($directory . $image->getName());
            $this->em->persist($image);
        }

        $this->em->persist($product);
        $this->em->flush();
    }
}
