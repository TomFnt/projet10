<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController
{
    #[Route("/project/add", name: 'project_add')]
    public function add(): Response
    {
        return true;
    }

    #[Route("/project/{id}", name: 'project_index', requirements: ['id' => '\d+'])]
    public function index(Project $project, TaskRepository $taskRepository): Response
    {

        $idProject = $project->getIdProject();
        $tasks = $taskRepository->findTasksByProject();

        return $this->render('project/project_index.html.twig', [
            "project" => $project,
            "tasks" => $tasks
        ]);
    }

    #[Route("/project/edit/{id}", name: 'project_edit')]
    public function edit(): Response
    {
        return true;
    }

}