<?php

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use App\Controller\Admin\DashboardController;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UserRepository $userRepository,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly Security $security,
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function getDoctrine()
    {
        return $this->doctrine->getManager();
    }

    #[Route(path: '/gestion/connexion', name: 'login', methods: ['GET', 'POST'])]
    public function adminLoginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $session = $this->requestStack->getSession();
        if (!$session->get('redirect')) {
            $session->set('redirect', '/gestion');
        }

        if (!is_null($this->security->getUser())) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($this->security->getUser()->getId());
            $user->setLastAuthentification(new \DateTime());
            $user->setLastIpAdress($request->getClientIp());
            $this->getDoctrine()->persist($user);
            $this->getDoctrine()->flush();
            return new RedirectResponse($this->generateUrl('gestion_dashboard'));
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('page/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/gestion/deconnexion', name: 'logout', methods: ['GET'])]
    public function adminLogout(): never
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
