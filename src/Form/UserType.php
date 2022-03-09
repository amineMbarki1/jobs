<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['constraints' => [
               new Email(['message' => 'Invalid Email'])
            ]])
            ->add('name', TextType::class, ['constraints' => [
                new Length(['min' => 3, 'max' => 8]),
                new Type(['type' => 'alpha', 'message' => 'Invalid Name'])
            ]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'constraints' => [new Length(['min' => 6, 'minMessage' => 'Password must have at least 6 characters'])],
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('Register', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
