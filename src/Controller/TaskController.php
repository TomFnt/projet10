<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController
{
    #[Route( name: 'task_add')]
    public function add(): Response
    {
        return true;
    }

    #[Route( name: 'task_show', requirements: ['id_task' => '\d+'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return true;
    }

    #[Route( name: 'task_edit', requirements: ['id_task' => '\d+'])]
    public function edit(): Response
    {
        return true;
    }

    #[Route( name: 'task_delete', requirements: ['id_task' => '\d+'])]
    public function delete(): Response
    {
        return true;
    }
}