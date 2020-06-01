<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    private const MIN_PASSWORD_LENGTH = 6;

    // max length allowed by Symfony for security reasons
    private const MAX_PASSWORD_LENGTH = 4096;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter valid email',
                    ]),
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control --check-email-on-typing',
                    'placeholder' => 'Email address',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => self::MIN_PASSWORD_LENGTH,
                        'max' => self::MAX_PASSWORD_LENGTH,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Password',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
