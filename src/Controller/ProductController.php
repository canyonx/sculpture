<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/realisations/{slug}', name: 'product')]
    public function index(
        Product $product,
    ): Response {

        $nbimages = count(glob($this->getParameter('images_dir') . $product->getSlug() . "/*.jpg"));

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'nbimages' => $nbimages,
        ]);
    }
}
