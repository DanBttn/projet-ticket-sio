<?php

namespace App\Form;

use App\Entity\Incident;
use App\Entity\Priority;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class IncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reporterEmail',EmailType::class, [
                'label' => ' Email',
                'attr' => [
                    'placeholder' => 'Email',
    ]
        ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Enter Description',
                    'rows' => 6,
    ]
    ])
            ->add('priority', EnumType::class, [
                'label' => 'Priority',
                'class' => Priority::class,
                    'label_attr' => [
                        'class' => 'checkbox-inline',
    ],
        'choice_label' => function ($priority) {
                return $priority->value;
    }
        ])
            ->add('types', EnumType::class ,[
                'label' => 'Type',
                'class' =>  Type::class,
                'label_attr' => [
                    'class' => 'checkbox-inline',
    ],
        'choice_label' => function ($types) {
                return $types->value;
    },
                "multiple" => true,
                'expanded' => true,
                'by_reference' => false,

    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Incident::class,
        ]);
    }
}
