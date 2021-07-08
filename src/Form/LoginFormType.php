<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank; 
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class LoginFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('email', EmailType::class, [
                    'attr' => ['autocomplete' => 'email'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter your email',
                                ]),
                    ],
                ])->add('password',PasswordType::class, [
            'attr' => ['autocomplete' => 'email'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your password',
                        ]),
                new Length([
                    'max' => 4096,
                        ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([]);
    }

}
