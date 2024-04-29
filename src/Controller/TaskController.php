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

            try {
                $this->entityManager->persist($task);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre nouvelle tâche a été enregistrées avec succès.');
            }
            catch(\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la création de cette tâche.");
            }

            return $this->redirectToRoute('task_show', ['id' => $task->getIdTask()]);
        }

        return $this->render('task/task-form-add.html.twig', [
            'form' => $form,
            'page_title' => "Créer une tâche"
        ]);

    }

    #[Route("/task/{id}" ,name: 'task_show', requirements: ['id' => '\d+'])]
    public function taskIndex(Task $task, Request $request): Response
    {

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        // Check if  $form are submitted & if modification are valid
        if ($form->isSubmitted() && $form->isValid()) {

            try{
            $task = $form->getData();

            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');
            }
            catch(\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la modification de cette tâche.");
            }

            return $this->redirectToRoute('task_show', ['id' => $task->getIdTask()]);
        }

        //simple view in case it's for show task information
        return $this->render('task/task-form-edit.html.twig', [
            'form' => $form->createView(),
            'page_title' => $task->getName(),
            'id_task' => $task->getIdTask(),
        ]);
    }

    #[Route(  "/task/delete/{id}", name: 'task_delete', requirements: ['id_task' => '\d+'])]
    public function delete(): Response
    {
        return true;
    }
}