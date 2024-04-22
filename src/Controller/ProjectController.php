<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController
{
    #[Route( name: 'project_show')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return true;
    }
    #[Route( name: 'project_add')]
    public function add(): Response
    {
        return true;
    }
    #[Route( name: 'project_edit')]
    public function edit(): Response
    {
        return true;
    }

}