<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class TaskController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('project/{id}/task/add', name: 'task_add', requirements: ['id' => '\d+'])]
    #[IsGranted("ROLE_ADMIN")]
    public function taskAdd(Project $project, Request $request): Response
    {
        $task = new Task();
        $project->addTask($task);

        $form = $this->createForm(TaskType::class, $task, [
            'project_id' => $project->getId(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            try {
                $this->entityManager->persist($task);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre nouvelle tâche a été enregistrées avec succès.');
            } catch (\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la création de cette tâche. ".$message);

                return $this->redirectToRoute('task_add');
            }

            return $this->redirectToRoute('task_index', ['id' => $task->getId()]);
        }

        return $this->render('task/task-form-add.html.twig', [
            'form' => $form,
            'page_title' => 'Créer une tâche',
            'display_nav'=> true
        ]);
    }

    #[Route('/task/{id}', name: 'task_index', requirements: ['id' => '\d+'])]
    public function taskIndex(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task,  [
            'project_id' => $task->getProject()->getId(),
        ]);
        $form->handleRequest($request);

        // Check if  $form are submitted & if modification are valid
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $task = $form->getData();

                $this->entityManager->persist($task);
                $this->entityManager->flush();
                $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');
            } catch (\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la modification de cette tâche.");
            }

            return $this->redirectToRoute('task_index', ['id' => $task->getId()]);
        }

        // simple view in case it's for show task information
        return $this->render('task/task-form-edit.html.twig', [
            'form' => $form->createView(),
            'page_title' => $task->getName(),
            'id_task' => $task->getId(),
        ]);
    }

    #[Route('/task/delete/{id}', name: 'task_delete', requirements: ['id_task' => '\d+'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Task $task): Response
    {
        $id = $task->getId();

        $this->entityManager->remove($task);

        try {
            $this->entityManager->flush();

            $this->addFlash('success', "Supression de la tâche n°$id réussi. ");
        } catch (Exception $message) {
            $this->addFlash('error', "Une erreur c'est produite lors de la suppression de la tâche n° $id.");
        }

        return $this->redirectToRoute('app_home');
    }
}
