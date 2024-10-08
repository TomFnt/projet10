<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Employee::statutEmployeeList, Employee::statutEmployeeList),
            ])
            ->add('date_add', DateType::class, [
                'label' => "Date d'entrée",
                'widget' => 'single_text',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Chef de projet' => 'ROLE_ADMIN',
                    'Collaborateur' => 'ROLE_USER',
                ],
                'multiple' => true, // Permet de sélectionner plusieurs rôles
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
