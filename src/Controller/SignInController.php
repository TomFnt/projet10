<?php

namespace App\Controller;

use App\Form\SignInType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SignInController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/sign/in', name: 'app_sign_in')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(SignInType::class);

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

        return $this->render('sign_in/base-login.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Sign in',
            'btn_label'=>'Sign in'
        ]);
    }
}
