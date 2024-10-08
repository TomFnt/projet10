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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProjectController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/project/add', name: 'project_add')]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: "Vous n'êtes pas autorisé à accéder à cette page.")]
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
                $this->addFlash('error', "Une erreur c'est produite lors de la création de ce projet.".$message);

                return $this->redirectToRoute('project_add');
            }

            return $this->redirectToRoute('project_index', ['id' => $project->getId()]);
        }

        return $this->render('project/project-form.html.twig', [
            'form' => $form,
            'display_nav' => 'true',
            'page_title' => 'Créer un projet',
            'btn_label' => 'Créer',
        ]);
    }

    #[Route('/project/{id}', name: 'project_index', requirements: ['id' => '\d+'])]
    #[IsGranted('project_access', 'id')]
    public function projectIndex(Project $project, TaskRepository $taskRepository, int $id): Response
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
            'display_nav' => true,
        ]);
    }

    #[Route('/project/edit/{id}', name: 'project_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: "Vous n'êtes pas autorisé à accéder à cette page.")]
    public function projectEdit(Project $project, Request $request): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        // Check if  $form are submitted & if modification are valid
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $project = $form->getData();

                $this->entityManager->persist($project);
                $this->entityManager->flush();
                $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');
            } catch (\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la modification de ce projet.");
            }

            return $this->redirectToRoute('project_index', ['id' => $project->getId()]);
        }

        return $this->render('project/project-form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Modifier le Projet : '.$project->getName(),
            'btn_label' => 'Modifier',
            'display_nav' => true,
        ]);
    }

    #[Route('/project/delete/{id}', name: 'project_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: "Vous n'êtes pas autorisé à accéder à cette page.")]
    public function projectDelete(Project $project): Response
    {
        $id = $project->getId();

        $this->entityManager->remove($project);

        try {
            $this->entityManager->flush();

            $this->addFlash('success', "Supression du projet n°$id réussi. ");
        } catch (Exception $message) {
            $this->addFlash('error', "Une erreur c'est produite lors de la suppression du projet n° $id.");
        }

        return $this->redirectToRoute('app_home');
    }
}
