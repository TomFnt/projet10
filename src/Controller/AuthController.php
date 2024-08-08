<?php

namespace App\Controller;

use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
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
    public function index(): Response
    {
        return $this->render('auth/login.html.twig', [
            'page_title' => 'Login',
            'display_nav' => false,
        ]);
    }

    #[Route(path: '/signin', name: 'app_sign_in')]
    public function signin(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Sign in',
            'display_nav' => false,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/signup', name: 'app_sign_up')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, GoogleAuthenticatorInterface $googleAuth): Response
    {
        $form = $this->createForm(SignUpType::class);

        $form->handleRequest($request);

        // Check if  $form are submitted & if value are valid
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $employee = $form->getData();
                $employee->setAvatar($employee->getName(), $employee->getSurname());
                $employee->setStatus('CDI');

                $hashPassword = $passwordHasher->hashPassword($employee, $employee->getPassword());
                $employee->setPassword($hashPassword);
                $employee->setGoogleAuthenticatorSecret($googleAuth->generateSecret());

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

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Sign up',
            'btn_label' => 'Sign up',
            'display_nav' => false,
        ]);
    }

    #[Route('/2fa/qrcode', name: '2fa_qrcode')]
    public function displayGoogleAuthenticatorQrCode(GoogleAuthenticatorInterface $googleAuthenticator): Response
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($googleAuthenticator->getQRContent($this->getUser()))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(200)
            ->margin(0)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        return new Response($result->getString(), 200, ['Content-Type' => 'image/png']);
    }

    #[Route('/2fa', name: '2fa_login')]
    public function displayGoogleAuthenticator(): Response
    {
        return $this->render('auth/2fa.html.twig', [
            'qrCode' => $this->generateUrl('2fa_qrcode'),
            'page_title' => 'double authentication',
            'display_nav' => false,
        ]);
    }
}
