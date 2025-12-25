<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MySpaceController extends AbstractController
{
    #[Route('/profile/mySpace', name: 'app_my_space')]
public function index(): Response
{
    $user = $this->getUser();

    if (!$user instanceof \App\Entity\User) {
        // Pas connecté -> redirection login (ou erreur 403)
        return $this->redirectToRoute('app_login');
    }

    $userName = ucfirst((string) $user->getName());
    $userFirstname = ucfirst((string) $user->getFirstname());
    $userEmail = (string) $user->getEmail();
    $userEmailVerifier = $user->isVerified();

    return $this->render('my_space/index.html.twig', [
        'name' => $userName,
        'firstname' => $userFirstname,
        'email' => $userEmail,
        'verified' => $userEmailVerifier,
    ]);
}

}
