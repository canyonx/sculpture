<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Setting;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\ProductCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('alain-dauchez.fr');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-home', 'homepage');

        yield MenuItem::linkToCrud('Sculptures', 'fas fa-list', Product::class);

        yield MenuItem::linkToCrud('Paramètres', 'fas fa-gear', Setting::class)
            // ->setQueryParameter('crudAction', 'edit')
            // ->setQueryParameter('entityId', 1);
        ;

        yield MenuItem::linkToCrud('Profil', 'fas fa-user', User::class)
            // ->setQueryParameter('crudAction', 'edit')
            // ->setQueryParameter('entityId', 3);
        ;
    }
}
