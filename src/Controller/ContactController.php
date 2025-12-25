<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class ContactController extends AbstractController
{
    #[Route('/profile/contact', name: 'app_contact')]
    public function index(MailerInterface $mailer, Request $request): Response
    {
        $user = $this->getUser();

        // Si tu veux obliger la connexion :
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $userName = ucfirst((string) $user->getName());
        $userFirstname = ucfirst((string) $user->getFirstname());
        $userEmail = (string) $user->getEmail();

        $adminEmail = $_ENV['ADMIN_EMAIL'] ?? 'admin@example.com';

        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();
            $message = $data['message'] ?? '';

            $mailer->send(
                (new TemplatedEmail())
                    ->from($adminEmail)            // ou ->from($userEmail) selon ton besoin
                    ->to($adminEmail)
                    ->replyTo($userEmail)
                    ->subject("Message provenant d'un utilisateur du site easywebjob")
                    ->htmlTemplate("contact/email.html.twig")
                    ->context([
                        "message" => $message,
                        "name" => $userName,
                        "firstname" => $userFirstname, // corrigé (pas "firtname")
                        "userEmail" => $userEmail,
                    ])
            );

            $this->addFlash("send", "Votre message a bien été envoyé.");

            return $this->redirectToRoute('app_contact'); // évite le double envoi au refresh
        }

        return $this->render('contact/index.html.twig', [
            'contactform' => $contactForm->createView()
        ]);
    }
}
