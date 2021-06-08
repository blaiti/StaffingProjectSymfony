<?php

namespace App\Form;

use App\Entity\Consultants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ConsultantFormType extends AbstractType
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
            'data_class' => Consultants::class,
        ]);
    }
}
