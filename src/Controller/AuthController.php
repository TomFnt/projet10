<?php

namespace App\Controller;

use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function index():Response
    {
        return $this->render('auth/base-login.html.twig', [
            'page_title' => 'Login',
            'display_nav'=> false
        ]);
    }
    #[Route(path: '/signin', name: 'app_sign_in')]
    public function signin(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/base-login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Sign in',
            'display_nav'=> false
        ]);
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/signup', name: 'app_sign_up')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(SignUpType::class);

        $form->handleRequest($request);

        // Check if  $form are submitted & if value are valid
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $employee = $form->getData();
                $employee->setAvatar($employee->getName(), $employee->getSurname());
                $employee->setStatus(1);

                $hashPassword = $passwordHasher->hashPassword($employee, $employee->getPassword());
                $employee->setPassword($hashPassword);

                $date = new \DateTime();
                $employee->setDateAdd($date);
                var_dump($employee);
                $this->entityManager->persist($employee);
                $this->entityManager->flush();
                $this->addFlash('success', "L'inscription a été enregistrée avec succès.");
            } catch (\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de l'inscription.".$message);
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('auth/base-login.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Sign up',
            'btn_label'=>'Sign up',
            'display_nav'=> false
        ]);
    }
}
