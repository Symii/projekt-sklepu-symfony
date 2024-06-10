<?php
// src/Controller/OrderController.php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderCustomerType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $cartService;
    private $entityManager;
    private $session;

    public function __construct(CartService $cartService, EntityManagerInterface $entityManager)
    {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
        $this->session = new Session();
    }
    
    /**
     * @Route("/order/checkout", name="order_checkout")
     */
    #[Route('/order/checkout', name: 'order_checkout')]
    public function checkout(Request $request): Response
    {
        $form = $this->createForm(OrderCustomerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerData = $form->getData();

            // Przetwórz zamówienie i zapisz je w bazie danych, zwróć ID zamówienia
            $orderId = $this->cartService->checkout($customerData);

            // Zapisz ID zamówienia w sesji
            $this->session->set('orderId', $orderId);

            // Przekieruj na stronę potwierdzenia zamówienia
            return $this->redirectToRoute('order_confirmation');
        }

        return $this->render('order_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/order/confirmation", name="order_confirmation")
     */
    #[Route('/order/confirmation', name: 'order_confirmation')]
    public function confirmation(): Response
    {
        // Pobierz ID zamówienia z sesji
        $orderId = $this->session->get('orderId');

        // Pobierz zamówienie z bazy danych
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        return $this->render('order_confirmation.html.twig', [
            'order' => $order,
        ]);
    }
}