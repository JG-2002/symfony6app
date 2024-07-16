<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
// use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
//include entities
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\MarqueProduit;
use App\Entity\CategorieProduit;
//mailler
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

#[Route('/gestion')]
class AdminDashboardController extends AbstractDashboardController
{
    private $user;
    private $request;
    private $fromEmail;
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ManagerRegistry $doctrine,
        private readonly Security $security,
        private readonly \Twig\Environment   $twig,
        private readonly MailerInterface       $mailer
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $user = $this->security->getUser();
        $this->fromEmail = 'no-reply@catecomsa.com';
        if (!empty($user)) {
            $em = $this->doctrine->getManager();
            $this->user = $em->getRepository(User::class)->findOneBy(['id' => $user->getId()]);
        }
    }

    #[Route('/', name: 'index_dashboard')]
    public function index(): Response
    {
        $em = $this->doctrine->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        return $this->render('admin/dashboard.html.twig', [
            "user" => $user,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Markein');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Produits', 'fa fa-book')->setSubItems([
            MenuItem::linkToCrud('Produits', '', Produit::class),
            MenuItem::linkToCrud('Catégories', '', CategorieProduit::class),
            MenuItem::linkToCrud('Marques', '', MarqueProduit::class)
        ])->setPermission('ROLE_ADMIN');
    }
}
