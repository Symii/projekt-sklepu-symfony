<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookieController extends AbstractController
{
    #[Route(path: '/accept-cookies', name: 'accept_cookies')]
    public function acceptCookies(Request $request): JsonResponse
    {
        $session = $request->getSession();
        if($session->get('cookies_accepted') == true) {
            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse(['success' => false]);
        }
        

        
    }

    #[Route(path: '/accept-cookies-true', name: 'accept_cookies_true')]
    public function acceptCookiesTrue(Request $request)
    {
        $session = $request->getSession();
        $session->set('cookies_accepted', true);
    }
}