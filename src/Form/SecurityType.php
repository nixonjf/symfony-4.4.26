<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SecurityType extends AbstractType {

    /**
     * Form Builder.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface {

        switch ($options['mode']){
            case 'userLogin':
                return $this->userLogin($builder, $options);
            break;   
            case 'forgotPassword':
                return $this->forgotPassword($builder, $options);
            break;
            case 'updatePassword':
                return $this->updatePassword($builder, $options);
            break;
            case 'resetPassword':
                return $this->updatePassword($builder, $options);
            break;
            default:
                return $this->userLogin($builder, $options);
            break;
        }
        
    }
    
    
    /**
     * Function to build user login from
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     * @return FormBuilderInterface
     */
    private function userLogin(FormBuilderInterface $builder, array $options){
        
        $builder
        ->add('code', TextType::class, [
        'attr' => array(
        'placeholder' => '000 000'
        ),
        'mapped' => false,
        'required' => true,
        'constraints' => [new NotBlank(), new Length(['max' => 6])],
        ])
        ->add('_username', TextType::class, [
        'attr' => array(
        'placeholder' => 'username'
        ),
        'mapped' => false,
        'required' => true,
        'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ])
        ->add('_password', PasswordType::class, [
        'attr' => array(
        'placeholder' => 'password.password'
        ),
        'required' => false,
        'mapped' => false,
        'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ]);

        return $builder;
    }
    
    /**
     * Function to build forgot password form
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     * @return FormBuilderInterface
     */
    private function forgotPassword(FormBuilderInterface $builder, array $options){
        
        $builder
        ->add('_username', TextType::class, [
        'label' => 'username',
        'attr' => array(
        'placeholder' => 'username'
        ),
        'mapped' => false,
        'required' => true,
        'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ]);

        return $builder;
    }
    
    /**
     * Function to build reset password form
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     * @return FormBuilderInterface
     */
    private function updatePassword(FormBuilderInterface $builder, array $options){
        
        $builder
        ->add('_password', PasswordType::class, [
        'label' => 'password.new_password',
        'attr' => array(
        'placeholder' => 'password.new_password'
        ),
        'mapped' => false,
        'required' => true,
        'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ])
        ->add('_confirm_password', PasswordType::class, [
        'label' => 'password.confirm_password',
        'attr' => array(
        'placeholder' => 'password.confirm_password'
        ),
        'mapped' => false,
        'required' => true,
        'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ]);
        

        if('resetPassword' ===  $options['mode']){   
        $builder->add('_reset_token', HiddenType::class, []);
        }

        return $builder;
    }
    

    /**
     * Configure options.
     *
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver) {
        $optionsResolver->setDefaults([
            'csrf_protection' => true,
            'translation_domain' => 'trans',
            'mode' => 'userLogin'
        ]);
    }

}
