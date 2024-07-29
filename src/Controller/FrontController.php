<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/* include emails modules */
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class FrontController extends AbstractController
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }


    #[Route('/', name: 'index_home_page', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/contact', name: 'index_contact_page', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        if ($request->getMethod() == 'POST') {
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $email = $request->get('email');
            $message = $request->get('message');
            if ($firstname && $lastname && $email && $message) {
                $email = (new Email())
                    ->from('contact@marketin.com')
                    ->replyTo(new Address($email, $firstname . $lastname))
                    ->to('contact@marketin.com')
                    ->subject('[MarketIn] Nouveau message')
                    ->text($message);
                if ($request->files->get('inputAttachment')) {
                    $email->attach(file_get_contents($request->files->get('inputAttachment')->getPathname()), $request->files->get('inputAttachment')->getClientOriginalName());
                }
                try {
                    $this->mailer->send($email);
                    $this->addFlash('success', 'Votre message a bien été envoyé');
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoie de l\'email, merci de rééssayer');
                }
            } else {
                $this->addFlash('error', "Tous les champs doivent être renseignés");
            }
        }

        return $this->render('front/contact.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
