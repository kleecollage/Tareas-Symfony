<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, array(
            'label' => 'Titulo',
        ));
        $builder->add('content', TextareaType::class, array(
            'label' => 'Contenido',
        ));
        $builder->add('priority', ChoiceType::class, array(
            'label' => 'Prioridad',
            'choices' => array(
                'Alta' => 'High',
                'Media' => 'Medium',
                'Baja' => 'Low',
            )
        ));
        $builder->add('hours', TextType::class, array(
            'label' => 'Horas Presupuestadas',
        ));
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Guardar',
        ));
    }
}