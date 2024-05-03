<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/project/add', name: 'project_add')]
    public function projectAdd(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            try {
                $this->entityManager->persist($project);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre nouveau projet a été enregistrées avec succès.');
            } catch (\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la création de ce projet. ".$message);

                return $this->redirectToRoute('project_add');
            }

            return $this->redirectToRoute('project_index', ['id' => $project->getId()]);
        }

        return $this->render('project/project-form.html.twig', [
            'form' => $form,
            'page_title' => 'Créer un projet',
            'btn_label' => 'Créer',
        ]);
    }

    #[Route('/project/{id}', name: 'project_index', requirements: ['id' => '\d+'])]
    public function projectIndex(Project $project, TaskRepository $taskRepository): Response
    {
        $toDoList = $taskRepository->findBy(['project' => $project, 'status' => 'To Do'], ['deadline' => 'ASC']);
        $DoingList = $taskRepository->findBy(['project' => $project, 'status' => 'Doing'], ['deadline' => 'ASC']);
        $doneList = $taskRepository->findBy(['project' => $project, 'status' => 'Done'], ['deadline' => 'ASC']);

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
