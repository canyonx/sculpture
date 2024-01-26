<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\MailerService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private MailerService $mailer,
        #[Autowire('%admin_email%')] private string $adminEmail,
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(
        ProductRepository $productRepository,
        Request $request,
    ): Response {

        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $context = (['message' => $formData]);
            $subject = 'Nouveau message de ' . $formData['name'];

            $this->mailer->sendEmail(subject: $subject, context: $context);

            $this->addFlash('success', 'Message envoyé');
            return $this->redirectToRoute('homepage', ['_fragment' => 'contact']);
        }

        // Image contact
        // $nbProducts = count($productRepository->findAll());
        // $image = $productRepository->find(rand(1, $nbProducts));

        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findBy([], ['year' => 'DESC'], 3),
            // 'user' => $userRepository->findOneBy([], []),
            // 'image' => $image->getPicture(),
            'form' => $form,
        ]);
    }
}
