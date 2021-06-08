<?php

namespace App\Form;

use App\Entity\Managers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ManagerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Seniority', ChoiceType::class, [
                'choices'  => [
                    'Senior' => 'Senior',
                    'Junior' => 'Junior',
                ],
            ])
            ->add('Projects')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Managers::class,
        ]);
    }
}
