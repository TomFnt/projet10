<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Create 5 random Employees
        for ($i = 1; $i <= 5; $i++) {

            $firstName = $faker->firstName();
            $surName = $faker->lastName();
            $avatar = substr($firstName, 0, 1).substr($surName, 0, 1);

            $employee = new Employee();
            $employee->setAvatar($avatar);
            $employee->setName($firstName);
            $employee->setSurname($surName);
            $employee->setEmail($faker->email());
            $employee->setDateAdd(new \DateTime());

            $manager->persist($employee);
            $this->addReference('employee_'.$i, $employee); // add reference for l52
        }

        // Create 3 random Project
        for ($i = 1; $i <= 3; $i++) {
            $project = new Project();
            $project->setName($faker->sentence(4)); // generate a phrase with 3 random words
            $project->setIsArchived($faker->boolean());

            $manager->persist($project);
            $this->addReference('project_'.$i, $project, ); // add reference for l53
        }

        // Create 4 random Task
        for ($i = 1; $i <= 4; $i++) {
            $task = new Task();
            $task->setName($faker->sentence(4));

            //attribute randomly an existent employee & project for this task
            $employeeRef = $this->getReference('employee_'.rand(1, 5));
            $projectRef = $this->getReference('project_'.rand(1, 3));
            $task->setEmployee($employeeRef);
            $task->setProject($projectRef);

            $task->setDescription($faker->sentence());
            $task->setDeadline($faker->dateTimeBetween('now', '+3 years'));
            $task->setStatut($faker->randomElement(['To Do', 'In Progress', 'Done']));

            $manager->persist($task);
        }

        $manager->flush();
    }
}
