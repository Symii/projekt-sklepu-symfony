<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/moje-konto", name="my_account")
     */
    #[Route('/moje-konto', name: 'my_account')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('my_account/index.html.twig');
    }

    /**
     * @Route("/zmien-haslo", name="change_password")
     */
    #[Route('/zmien-haslo', name: 'change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if ($user === null) {
            return $this->redirectToRoute('product_list');
        }
        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            throw new \LogicException('Obiekt użytkownika nie implementuje interfejsu PasswordAuthenticatedUserInterface.');
        }
        $email = SecurityRequestAttributes::LAST_USERNAME;

        $newPassword = $request->request->get('new_password');
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);

        // Ustaw hasło użytkownika na nowo hashowane hasło
        $user->setPassword($hashedPassword);

        // Zapisz zmiany w bazie danych
        $entityManager->flush();

        // Powiadom użytkownika o udanej zmianie hasła
        $this->addFlash('success', 'Hasło zostało zmienione.');

        return $this->redirectToRoute('my_account'); // Przekieruj gdziekolwiek chcesz
    }

}