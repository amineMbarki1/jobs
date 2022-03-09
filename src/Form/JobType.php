<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', TextType::class, [
                'attr' => ['placeholder' => 'Company name ...']
            ])
            ->add('logo', FileType::class, ['mapped' => false, 'required' => false])
            ->add('position', TextType::class, [
                'attr' => ['placeholder' => 'Name of the position ...']
            ])
            ->add('role', TextType::class, [
                'attr' => ['placeholder' => 'Overview of the position ...']
            ])
            ->add('contract', ChoiceType::class, [
                'choices' => ['Part time' => 'Part Time', 'Full Time' => 'Full Time']
            ])
            ->add('location', TextType::class, [
                'attr'=>['placeholder' => 'Job Location ...']
            ] )
            ->add('website', UrlType::class, [
                'attr' => ['placeholder'=> 'Company Website: https://example.com ...']
            ])
            ->add('apply', UrlType::class, [
                'attr' => ['placeholder' => 'Application Website: http://example.com/apply ...']
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['placeholder' => 'General Description of the Job Posting ...']
            ])
            ->add('requirementsDescription', TextareaType::class, [
                'attr' => ['placeholder' => 'Overview of the requirements of the ideal candidate ...']
            ])
            ->add('requirements',  TextareaType::class , [
                'attr' => ['placeholder' => 'Input the specific Requirements One by One seperated by dash (-) ...']
            ])
            ->add('responsibilities', TextareaType::class , [
                'attr' => ['placeholder' => 'Input the specific Responsibilities One by One  seperated by dash (-) ...']
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
