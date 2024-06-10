<?php
namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->session = new Session();
        $this->entityManager = $entityManager;
    }

    public function add(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        $this->session->set('cart', $cart);
        $itemCount = $this->getItemCount();
        $this->session->set('cartItemCount', $itemCount);
    }

    public function remove(int $id): void
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
        $itemCount = $this->getItemCount();
        $this->session->set('cartItemCount', $itemCount);
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->entityManager->getRepository(Product::class)->find($id);

            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartWithData;
    }

    public function getTotal(): float
    {
        $total = 0;
        $cart = $this->getCart();

        foreach ($cart as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    public function getItemCount(): int
    {
        $cart = $this->session->get('cart', []);
        $itemCount = 0;

        foreach ($cart as $quantity) {
            $itemCount += $quantity;
        }

        return $itemCount;
    }

    public function checkout(array $customerData): int
    {
        $cart = $this->session->get('cart', []);
        $order = new Order();

        foreach ($cart as $productId => $quantity) {
            $product = $this->entityManager->getRepository(Product::class)->find($productId);
            if ($product) {
                $order->addProduct($product, $quantity);
            }
        }

        $order->setTotal($this->getTotal());
        $order->setCreatedAt(new \DateTime());
        $order->setCustomerData($customerData);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Wyczyszczenie koszyka
        $this->session->set('cart', []);
        $this->session->set('cartItemCount', 0);

        // Zwróć ID zamówienia
        return $order->getId();
    }
}