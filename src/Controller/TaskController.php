<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,)
    {

    }

    #[Route("/task/add" ,name: 'task_add')]
    public function taskAdd(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('task_show', ['id' => $task->getIdTask()]);
        }

        return $this->render('task/task-form.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route("/task/{id}" ,name: 'task_show', requirements: ['id' => '\d+'])]
    public function taskIndex(Task $task): Response
    {

        return $this->render('task/task.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route( "/task/edit/{id}",name: 'task_edit', requirements: ['id_task' => '\d+'])]
    public function taskEdit(): Response
    {
        return true;
    }

    #[Route(  "/task/delete/{id}", name: 'task_delete', requirements: ['id_task' => '\d+'])]
    public function delete(): Response
    {
        return true;
    }
}