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
            $email = $firstName.".".$surName."@".$faker->freeEmailDomain();

            $employee = new Employee();
            $employee->setAvatar($avatar);
            $employee->setName($firstName);
            $employee->setSurname($surName);
            $employee->setEmail($email);
            $employee->setStatut($faker->randomElement(['CDI', 'CDD', 'Freelance', 'Alternant']));
            $employee->setDateAdd($faker->dateTimeBetween('-8 year', 'now'));

            $employees[]= $employee;
            $manager->persist($employee);
        }

        // Create 3 random Project
        for ($i = 1; $i <= 3; $i++) {
            $project = new Project();
            $project->setName($faker->sentence(4)); // generate a phrase with 3 random words
            $project->setIsArchived($faker->boolean());

            $projects[]= $project;
            $manager->persist($project);

        }

        // Get all employees and projects for in order to associate an random employee and project foreach task

        // Create 8 random Task
        for ($i = 1; $i <= 8; $i++) {
            $task = new Task();
            $task->setName($faker->sentence(4));
            $task->setDescription($faker->sentence());
            $task->setDeadline($faker->dateTimeBetween('now', '+3 years'));
            $task->setStatut($faker->randomElement(['To Do', 'In Progress', 'Done']));

            $manager->persist($task);

            $employee = $faker->randomElement($employees);
            $employee->addTask($task);

            $project = $faker->randomElement($projects);
            $project->addTask($task);
        }

        $manager->flush();
    }
}
