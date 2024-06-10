<?php
namespace App\EventListener;

use App\Service\CartService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class CartListener
{
    private $cartService;
    private $twig;
    private $logger;

    public function __construct(CartService $cartService, Environment $twig, LoggerInterface $logger)
    {
        $this->cartService = $cartService;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        throw new Exception('test');
        $response = $event->getResponse();
        $content = $response->getContent();

        // Dodajemy zmienną globalną do każdego szablonu Twig
        $content = $this->twig->render('global_variable.html.twig', [
            'cartItemCount' => $this->cartService->getItemCount()
        ]) . $content;

        $response->setContent($content);
        $event->setResponse($response);
    }

    public function onKernelController(ControllerEvent $event)
    {
        $itemCount = $this->cartService->getItemCount();
        $this->twig->addGlobal('cartItemCount', $itemCount);

        // Debugging
        $this->logger->info('CartListener is called.');
        $this->logger->info('Cart item count: ' . $itemCount);
    }
}