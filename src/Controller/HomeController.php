<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route(name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $role = $this->getUser()->getRoles();

        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $projectRepository->findAll();
        } else {
            $projects = $projectRepository->findProjectsByEmployee($this->getUser());
        }

        return $this->render('home.html.twig', [
            'projects' => $projects,
            'page_title' => 'Projets',
            'display_nav' => true,
        ]);
    }
}
