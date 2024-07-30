<?php

namespace App\Controller;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/* include emails modules */
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
/* database */
use Doctrine\Persistence\ManagerRegistry;

class FrontController extends AbstractController
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly MailerInterface $mailer
    ) {
    }

    public function getDoctrine(): mixed
    {
        return $this->doctrine->getManager();
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

    #[Route('/newsletter', name: 'index_newsletter_page', methods: ['POST'])]
    public function newsletter(Request $request): Response
    {
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine();

            $name = $request->get('name');
            $email = $request->get('email');
            if ($name && $email ) {
                $newsletter = $em->getRepository(Newsletter::class)->findOneBy([
                    'email' => $email
                ]);
                if(!$newsletter){
                    $newsletter = new Newsletter();
                    $newsletter->setName($name);
                    $newsletter->setEmail($email);
                    $em->persist($newsletter);
                    $em->flush();

                    /*
                     * Envoie email ajout à la newsletter
                     */
                    $email = (new Email())
                        ->from('contact@marketin.com')
                        //->replyTo(new Address($email, $name))
                        ->to($email)
                        ->subject('Ajout à la newsletter')
                        ->html("<p>Chers clients,</p><p>Chez MarketIn, nous sommes ravis de vous offrir des contenus exclusifs, des conseils marketing de pointe, et des offres spéciales directement dans votre boîte mail.</p><p>Nous sommes impatients de vous compter parmi nos abonnés.<br/>Cordialement,<br/>L'équipe MarketIn</p>");
                    if ($request->files->get('inputAttachment')) {
                        $email->attach(file_get_contents($request->files->get('inputAttachment')->getPathname()), $request->files->get('inputAttachment')->getClientOriginalName());
                    }
                    try {
                        $this->mailer->send($email);
                        $this->addFlash('success', 'vous avez été ajouté à la newsletter');
                    } catch (TransportExceptionInterface $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors de l\'envoie de l\'email, merci de rééssayer');
                    }
                }
            } else {
                $this->addFlash('error', "Tous les champs doivent être renseignés");
            }
        }

         return $this->redirect($request->headers->get('referer'));;
    }
}
