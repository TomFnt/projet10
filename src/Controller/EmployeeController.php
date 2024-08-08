<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class EmployeeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/employees', name: 'employees_index')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'page_title' => 'Équipe',
            'display_nav' => true,
        ]);
    }

    #[Route('/employee/edit/{id}', name: 'employee_edit', requirements: ['id' => '\d+'])]
    public function employeeEdit(Employee $employee, Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        // Check if  $form are submitted & if modification are valid
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $employee = $form->getData();

                $employee->setAvatar($employee->getName(), $employee->getSurname());
                $this->entityManager->persist($employee);
                $this->entityManager->flush();
                $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');
            } catch (\Exception $message) {
                $this->addFlash('error', "Une erreur c'est produite lors de la modification de ce projet.".$message);
            }

            return $this->redirectToRoute('employees_index');
        }

        return $this->render('employee/employee-form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Modifier le Projet : '.$employee->getFullName(),
            'btn_label' => 'Modifier',
            'display_nav' => true,
        ]);
    }

    #[Route('/employee/delete/{id}', name: 'employee_delete', requirements: ['id' => '\d+'])]
    public function employeeDelete(Employee $employee): Response
    {
        $id = $employee->getId();

        $this->entityManager->remove($employee);

        try {
            $this->entityManager->flush();

            $this->addFlash('success', "Supression de l'employé n°$id réussi. ");
        } catch (Exception $message) {
            $this->addFlash('error', "Une erreur c'est produite lors de la suppression de l'employé n° $id.");
        }

        return $this->redirectToRoute('employees_index');
    }
}
