<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_index')]
    public function index(): Response
    {
        return $this->render('front/cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart-chechout', name: 'app_cart_chechout_index')]
    public function chechout(): Response
    {
        return $this->render('front/cart/checkout.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }
}
