<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('deadline', null, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Task::$statusList, Task::$statusList),
            ])

            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                    return $er->createQueryBuilder('e')
                        ->where(':projectId MEMBER OF e.projects')
                        ->setParameter('projectId', $options['project_id']);
                },
                'choice_label' => function (Employee $employee) {
                    return $employee->getFullName();
                },
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'project_id' => null,
        ]);
    }
}
