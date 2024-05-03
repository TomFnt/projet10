<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/project/add', name: 'project_add')]
    public function projectAdd(): Response
    {
        return true;
    }

    #[Route('/project/{id}', name: 'project_index', requirements: ['id' => '\d+'])]
    public function projectIndex(Project $project, TaskRepository $taskRepository): Response
    {

        $toDoList = $taskRepository->findBy(['project' => $project, 'status' =>'To Do'], ['deadline'=>'ASC']);
        $DoingList = $taskRepository->findBy(['project' => $project, 'status' =>'Doing'], ['deadline'=>'ASC']);
        $doneList = $taskRepository->findBy(['project' => $project, 'status' =>'Done'], ['deadline'=>'ASC']);


        return $this->render('project/project_index.html.twig', [
            'project' => $project,
            'page_title' => $project->getName(),
            'todo_list' => $toDoList,
            'doing_list' => $DoingList,
            'done_list' => $doneList,
        ]);
    }

    #[Route('/project/edit/{id}', name: 'project_edit')]
    public function projectEdit(): Response
    {
        return true;
    }
}
