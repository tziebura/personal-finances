<?php


namespace App\Form;


use App\DTO\UserRegistrationRequest;
use App\Validator\UniqueUsername;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class UserRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form_label.email',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new UniqueUsername([
                        'message' => 'form_validation.user_{{ string }}_already_exists'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'form_validation.password_fields_must_match',
                'first_options'  => [
                    'label' => 'form_label.password',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 8,
                            'max' => 50,
                            'minMessage' => 'form_validation.password_must_be_at_least_{{ limit }}_characters_long',
                            'maxMessage' => 'form_validation.password_cannot_be_longer_than_{{ limit }}_characters',
                        ])
                    ]
                ],
                'second_options' => ['label' => 'form_label.repeat_password'],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationRequest::class
        ]);
    }
}