<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Create 5 random Employees
        for ($i = 1; $i <= 5; ++$i) {
            $firstName = $faker->firstName();
            $surName = $faker->lastName();
            $email = $firstName.'.'.$surName.'@'.$faker->freeEmailDomain();

            $plainPassword = $faker->password();

            $employee = new Employee();
            $employee->setAvatar($firstName, $surName);
            $employee->setName($firstName);
            $employee->setSurname($surName);
            $employee->setEmail($email);
            $hashedPassword = $this->passwordHasher->hashPassword($employee, $plainPassword);
            $employee->setPassword($hashedPassword);
            $employee->setStatus($faker->randomElement(['CDI', 'CDD', 'Freelance', 'Alternant']));
            $employee->setDateAdd($faker->dateTimeBetween('-8 year', 'now'));

            $employees[] = $employee;
            $manager->persist($employee);
        }

        // Create 3 random Project
        for ($i = 1; $i <= 3; ++$i) {
            $project = new Project();
            $project->setName($faker->sentence(4)); // generate a phrase with 3 random words
            $project->setIsArchived($faker->boolean());

            $projects[] = $project;
            $manager->persist($project);
        }

        // Get all employees and projects for in order to associate an random employee and project foreach task

        // Create 8 random Task
        for ($i = 1; $i <= 8; ++$i) {
            $task = new Task();
            $task->setName($faker->sentence(4));
            $task->setDescription($faker->sentence());
            $task->setDeadline($faker->dateTimeBetween('now', '+3 years'));
            $task->setStatus($faker->randomElement(['To Do', 'In Progress', 'Done']));

            $manager->persist($task);

            $employee = $faker->randomElement($employees);
            $employee->addTask($task);

            $project = $faker->randomElement($projects);
            $project->addTask($task);
        }

        $manager->flush();
    }
}
