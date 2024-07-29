<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignInController extends AbstractController
{
    #[Route('/sign/in', name: 'app_sign_in')]
    public function index(): Response
    {
        return $this->render('sign_in/base-login.html.twig', [
            'controller_name' => 'SignInController',
            'page_title' => 'Sign in',
        ]);
    }
}
