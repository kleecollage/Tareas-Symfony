<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Nombre',
        ));
        $builder->add('surname', TextType::class, array(
            'label' => 'Apellidos',
        ));
        $builder->add('email', EmailType::class, array(
            'label' => 'Corre Electronico',
        ));
        $builder->add('password', PasswordType::class, array(
            'label' => 'Contraseña',
        ));
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Registrarse',
        ));
    }
}