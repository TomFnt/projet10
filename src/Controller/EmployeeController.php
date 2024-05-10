<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    #[Route('/employees', name: 'employee_index')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'page_title' => 'Équipe',
        ]);
    }
    #[Route('/employee/edit/{id}', name: 'employee_edit', requirements: ['id' => '\d+'])]
    public function employeeEdit(): Response
    {

        return $this->render('employee/index.html.twig', [
            'page_title' => 'Équipe',
        ]);
    }
    #[Route('/employee/delete/{id}', name: 'employee_delete', requirements: ['id' => '\d+'])]
    public function employeeDelete(): Response
    {
        return $this->render('employee/index.html.twig', [
            'page_title' => 'Équipe',
        ]);
    }


}
