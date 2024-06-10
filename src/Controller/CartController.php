<?php
namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CartController extends AbstractController
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/cart', name: 'cart_index')]
    public function index(): Response
    {
        $cart = $this->cartService->getCart();
        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $cart,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id): RedirectResponse
    {
        $this->cartService->add($id);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(int $id): RedirectResponse
    {
        $this->cartService->remove($id);

        return $this->redirectToRoute('cart_index');
    }
}
