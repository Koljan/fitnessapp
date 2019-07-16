<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class)
        ->add('middlename', TextType::class)
        ->add('lastname', TextType::class)
        ->add('birthdate', BirthdayType::class)
        ->add('email', EmailType::class)
        ->add('gender', ChoiceType::class, [
            'choices' => [
                'Male'   => true,
                'Female' => false
            ]
        ])
        ->add('phone', TelType::class)
        ;
    }
}
