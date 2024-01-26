<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RealisationsController extends AbstractController
{
    #[Route('/realisations', name: 'realisations')]
    public function index(
        ProductRepository $productRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {

        $query = $productRepository->findAll();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        return $this->render('realisations/index.html.twig', [
            'products' => $pagination,
        ]);
    }
}
